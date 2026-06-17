<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMahasiswa;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengembalianRuanganController extends Controller
{
    /**
     * Tampilkan daftar pengajuan pengembalian ruangan
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil peminjaman yang sudah mengajukan pengembalian tapi belum disetujui
        $labNames = $user->laborans()->pluck('Laboratorium');
        if ($labNames->isEmpty()) {
            abort(403, 'Anda tidak memiliki akses laboratorium.');
        }

        $pengajuan_pengembalian = PeminjamanRuangan::with('daftarLab')
            ->where('pengajuan_pengembalian', true)
            ->whereNull('pengembalian_disetujui')
            ->whereHas('daftarLab', function ($q) use ($labNames) {
                $q->whereIn('Nama_Laboratorium', $labNames);
            })
            ->orderBy('tanggal_pengajuan_pengembalian', 'desc')
            ->get();

        // Ambil riwayat pengembalian yang sudah diproses
        $riwayat_pengembalian = PeminjamanRuangan::with('daftarLab')
            ->where('pengajuan_pengembalian', true)
            ->whereNotNull('pengembalian_disetujui')
            ->whereHas('daftarLab', function ($q) use ($labNames) {
                $q->whereIn('Nama_Laboratorium', $labNames);
            })
            ->orderBy('tanggal_persetujuan_pengembalian', 'desc')
            ->paginate(20);

        return view('laboran.pengembalian-ruangan.index', compact(
            'user',
            'pengajuan_pengembalian',
            'riwayat_pengembalian'
        ));
    }

    /**
     * Tampilkan detail pengajuan pengembalian
     */
    public function show($id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->daftarLab?->Nama_Laboratorium);

        return view('laboran.pengembalian-ruangan.detail', compact('user', 'peminjaman'));
    }

    /**
     * Setujui pengembalian ruangan
     */
    public function setujui(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->daftarLab?->Nama_Laboratorium);

        // Validasi: sudah mengajukan pengembalian
        if (! $peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Mahasiswa belum mengajukan pengembalian untuk ruangan ini.');
        }

        // Validasi: belum pernah disetujui/ditolak
        if ($peminjaman->pengembalian_disetujui !== null) {
            return back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_laboran' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Update status pengembalian
            $peminjaman->update([
                'pengembalian_disetujui' => true,
                'tanggal_persetujuan_pengembalian' => now(),
                'laboran_nama' => $user->Nama,
                'catatan_laboran' => $request->catatan_laboran,
                'status' => 'dikembalikan', // Update status peminjaman
            ]);

            // Catat aktivitas mahasiswa
            AktivitasMahasiswa::create([
                'user_nama' => $peminjaman->user_nama,
                'daftar_lab_id' => $peminjaman->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Ruangan Disetujui',
                'keterangan' => 'Pengembalian ruangan "'.$peminjaman->daftarLab->Nama_Laboratorium
                    .'" telah disetujui oleh '.$user->Nama
                    .' (Kondisi: '.ucfirst(str_replace('_', ' ', $peminjaman->kondisi_ruangan)).')',
            ]);

            DB::commit();

            Log::info('Pengembalian ruangan disetujui', [
                'peminjaman_id' => $peminjaman->id,
                'ruangan' => $peminjaman->daftarLab->Nama_Laboratorium,
                'mahasiswa' => $peminjaman->user_nama,
                'laboran' => $user->Nama,
            ]);

            return redirect()->route('laboran.pengembalian-ruangan.index')
                ->with('success', 'Pengembalian ruangan berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menyetujui pengembalian ruangan', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
            ]);

            return back()->with('error', 'Gagal menyetujui pengembalian: '.$e->getMessage());
        }
    }

    /**
     * Tolak pengembalian ruangan
     */
    public function tolak(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);

        $this->authorizeLaboranLab($user, $peminjaman->daftarLab?->Nama_Laboratorium);

        $this->authorizeLaboranLab($user, $peminjaman->daftarLab?->Nama_Laboratorium);

        // Validasi: sudah mengajukan pengembalian
        if (! $peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Mahasiswa belum mengajukan pengembalian untuk ruangan ini.');
        }

        // Validasi: belum pernah disetujui/ditolak
        if ($peminjaman->pengembalian_disetujui !== null) {
            return back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_laboran' => 'required|string|max:500',
        ], [
            'catatan_laboran.required' => 'Alasan penolakan harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Update status pengembalian
            $peminjaman->update([
                'pengembalian_disetujui' => false,
                'tanggal_persetujuan_pengembalian' => now(),
                'laboran_nama' => $user->Nama,
                'catatan_laboran' => $request->catatan_laboran,
                'pengajuan_pengembalian' => false, // Reset agar bisa mengajukan lagi
            ]);

            // Catat aktivitas mahasiswa
            AktivitasMahasiswa::create([
                'user_nama' => $peminjaman->user_nama,
                'daftar_lab_id' => $peminjaman->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Ruangan Ditolak',
                'keterangan' => 'Pengembalian ruangan "'.$peminjaman->daftarLab->Nama_Laboratorium
                    .'" ditolak oleh '.$user->Nama
                    .'. Alasan: '.$request->catatan_laboran,
            ]);

            DB::commit();

            Log::info('Pengembalian ruangan ditolak', [
                'peminjaman_id' => $peminjaman->id,
                'ruangan' => $peminjaman->daftarLab->Nama_Laboratorium,
                'mahasiswa' => $peminjaman->user_nama,
                'laboran' => $user->Nama,
                'alasan' => $request->catatan_laboran,
            ]);

            return redirect()->route('laboran.pengembalian-ruangan.index')
                ->with('success', 'Pengembalian ruangan ditolak. Mahasiswa dapat mengajukan kembali.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menolak pengembalian ruangan', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id,
            ]);

            return back()->with('error', 'Gagal menolak pengembalian: '.$e->getMessage());
        }
    }

    private function authorizeLaboranLab($user, ?string $labName): void
    {
        if (! $labName) {
            abort(404, 'Laboratorium tidak ditemukan.');
        }

        $hasAccess = $user->laborans()
            ->where('Laboratorium', $labName)
            ->exists();

        if (! $hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke laboratorium ini.');
        }
    }
}
