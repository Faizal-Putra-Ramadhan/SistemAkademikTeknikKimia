<?php

namespace App\Http\Controllers\PenelitiEksternal;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanRuanganMail;
use App\Models\AktivitasMahasiswa;
use App\Models\BebasLabRequest;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PeminjamanRuanganController extends Controller
{
    /**
     * Tampilkan form peminjaman ruangan untuk lab tertentu
     */
    public function create($labId)
    {
        $lab = DaftarLab::where('id', $labId)->first();
        $user = Auth::user();
        $labs = DaftarLab::all();

        // Kalau tidak ada lab sama sekali
        if ($labs->isEmpty()) {
            return view('peneliti-eksternal.pinjam-ruangan', [
                'lab' => null,
                'user' => $user,
                'labs' => $labs,
                'peminjaman_ruangans' => collect(),
                'peminjaman_aktif_per_lab' => [],
            ]);
        }

        // Ambil riwayat peminjaman ruangan peneliti eksternal ini
        $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Ambil peminjaman aktif untuk setiap lab (untuk warning box)
        $peminjaman_aktif_per_lab = [];
        foreach ($labs as $labItem) {
            $peminjaman_aktif_per_lab[$labItem->id] = PeminjamanRuangan::where('daftar_lab_id', $labItem->id)
                ->whereIn('status', ['menunggu', 'disetujui_laboran', 'menunggu_kaprodi', 'disetujui'])
                ->where('tanggal_selesai', '>=', now()->format('Y-m-d'))
                ->orderBy('tanggal', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->get();
        }

        return view('peneliti-eksternal.pinjam-ruangan', compact('lab', 'user', 'labs', 'peminjaman_ruangans', 'peminjaman_aktif_per_lab'));
    }

    /**
     * Simpan peminjaman ruangan baru
     */
    public function store(Request $request, $labId)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keperluan' => 'required|string|max:500',
        ], [
            'tanggal.required' => 'Tanggal mulai wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'keperluan.required' => 'Keperluan wajib diisi.',
            'keperluan.max' => 'Keperluan maksimal 500 karakter.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $tanggalMulai = $request->input('tanggal');
            $tanggalSelesai = $request->input('tanggal_selesai', $tanggalMulai);
            $jamMulai = $request->input('jam_mulai');
            $jamSelesai = $request->input('jam_selesai');

            if ($jamMulai && $jamSelesai) {
                $sameDate = $tanggalMulai && $tanggalSelesai && $tanggalMulai === $tanggalSelesai;

                if ($sameDate && $jamSelesai < $jamMulai) {
                    $validator->errors()->add('jam_selesai', 'Jam selesai tidak boleh lebih kecil dari jam mulai jika tanggal sama.');
                }
            }
        });

        $validated = $validator->validate();

        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);

        Log::info('Memulai peminjaman ruangan', [
            'user' => $user->Nama,
            'lab_id' => $labId,
            'data' => $validated,
        ]);

        DB::beginTransaction();
        try {
            // Cek ketersediaan ruangan di dalam transaksi untuk mencegah race condition
            $konflik = $this->cekKetersediaanRuangan(
                $labId,
                $validated['tanggal'],
                $validated['tanggal_selesai'],
                $validated['jam_mulai'],
                $validated['jam_selesai'],
                true
            );

            if ($konflik) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'Ruangan sedang dipakai pada tanggal dan jam tersebut. Silakan pilih waktu lain atau periksa jadwal yang sudah terpakai.');
            }

            // ✅ DEACTIVATE BEBAS LAB - Jika peneliti eksternal membuat peminjaman ruangan baru, deactivate bebas lab mereka
            $activeBebasLabs = BebasLabRequest::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($activeBebasLabs as $bebasLab) {
                $bebasLab->deactivate();
            }

            // Simpan peminjaman ruangan
            $peminjaman = PeminjamanRuangan::create([
                'user_id' => $user->id,
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'tanggal' => $validated['tanggal'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'keperluan' => $validated['keperluan'],
                'status' => 'menunggu',
            ]);

            // Catat aktivitas
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'jenis_aktivitas' => 'Peminjaman Ruangan',
                'keterangan' => "Mengajukan peminjaman ruangan {$lab->Nama_Laboratorium} dari {$validated['tanggal']} sampai {$validated['tanggal_selesai']}",
            ]);

            // ✅ KIRIM EMAIL KE LABORAN (mendukung multi-role & multiple labs scheme)
            $laborans = DaftarUser::laboranForLab($lab)->get();

            foreach ($laborans as $laboran) {
                $recipientEmail = $laboran->getNotificationEmail();
                if ($recipientEmail) {
                    try {
                        Mail::to($recipientEmail)->send(
                            new PeminjamanRuanganMail($peminjaman->load('daftarLab'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman ruangan berhasil dikirim ke laboran: ' . $recipientEmail);
                    }
                    catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke laboran: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return redirect()->route('peneliti-eksternal.pinjam-ruangan', ['labId' => $labId])
                ->with('success', 'Peminjaman ruangan berhasil diajukan! Email notifikasi telah dikirim ke laboran. Menunggu persetujuan.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan peminjaman ruangan', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Cek apakah ruangan tersedia pada tanggal dan jam tertentu
     */
    private function cekKetersediaanRuangan($labId, $tanggalMulai, $tanggalSelesai, $jamMulai, $jamSelesai, $lock = false)
    {
        Log::info('Cek ketersediaan ruangan', [
            'lab_id' => $labId,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        $query = PeminjamanRuangan::where('daftar_lab_id', $labId)
            ->whereIn('status', ['menunggu', 'disetujui_laboran', 'menunggu_kaprodi', 'disetujui']);

        if ($lock) {
            $query->lockForUpdate();
        }

        $peminjamanAktif = $query->get();

        Log::info('Total peminjaman aktif ditemukan', ['count' => $peminjamanAktif->count()]);

        foreach ($peminjamanAktif as $peminjaman) {
            $tanggalOverlap = $this->checkDateOverlap(
                $tanggalMulai,
                $tanggalSelesai,
                $peminjaman->tanggal,
                $peminjaman->tanggal_selesai
            );

            if ($tanggalOverlap) {
                $jamOverlap = $this->checkTimeOverlap(
                    $jamMulai,
                    $jamSelesai,
                    $peminjaman->jam_mulai,
                    $peminjaman->jam_selesai
                );

                if ($jamOverlap) {
                    Log::warning('Konflik ditemukan', [
                        'peminjaman_id' => $peminjaman->id,
                        'user' => $peminjaman->user_nama,
                        'tanggal' => $peminjaman->tanggal . ' - ' . $peminjaman->tanggal_selesai,
                        'jam' => $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai,
                    ]);

                    return true;
                }
            }
        }

        Log::info('Tidak ada konflik, ruangan tersedia');

        return false;
    }

    private function checkDateOverlap($start1, $end1, $start2, $end2)
    {
        return !($end1 < $start2 || $start1 > $end2);
    }

    private function checkTimeOverlap($start1, $end1, $start2, $end2)
    {
        return !($end1 < $start2 || $start1 > $end2);
    }

    /**
     * Ajukan pengembalian ruangan oleh peneliti eksternal
     */
    public function ajukanPengembalian(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanRuangan::findOrFail($id);

        if ($peminjaman->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengajukan pengembalian untuk peminjaman ini.');
        }

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa dikembalikan.');
        }

        if ($peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Anda sudah mengajukan pengembalian untuk ruangan ini.');
        }

        $request->validate([
            'keterangan_pengembalian' => 'nullable|string|max:500',
            'kondisi_ruangan' => 'required|in:baik,perlu pembersihan,rusak',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'pengajuan_pengembalian' => true,
                'tanggal_pengajuan_pengembalian' => now(),
                'keterangan_pengembalian' => $request->keterangan_pengembalian,
                'kondisi_ruangan' => $request->kondisi_ruangan,
            ]);

            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $peminjaman->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Ruangan',
                'keterangan' => 'Mengajukan pengembalian ruangan ' . $peminjaman->daftarLab->Nama_Laboratorium
                . ' (Kondisi: ' . ucfirst(str_replace('_', ' ', $request->kondisi_ruangan)) . ')',
            ]);

            DB::commit();

            return back()->with('success', 'Pengajuan pengembalian ruangan berhasil diajukan! Menunggu verifikasi dari laboran.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengajukan pengembalian ruangan', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengajukan pengembalian: ' . $e->getMessage());
        }
    }
}
