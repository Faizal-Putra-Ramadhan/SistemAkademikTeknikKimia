<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanAlatMail;
use App\Models\AktivitasMahasiswa;
use App\Models\AlatLab;
use App\Models\BebasLabRequest;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\RiskAssessment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PeminjamanAlatController extends Controller
{
    /**
     * Tampilkan form peminjaman
     */
    public function create($lab_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $labs = DaftarLab::penelitian()->get();

        
        if ($labs->isEmpty()) {
            return view('mahasiswa.pinjam-alat', [
                'lab' => null,
                'labs' => $labs,
                'user' => $user,
                'peminjaman_alats' => collect(),
                'riskAssessment' => null,
                'riskAssessments' => collect(),
                'masihBerlaku' => false,
                'pesanBatasWaktu' => 'Belum ada laboratorium yang tersedia saat ini.',
                'sisaWaktu' => null,
                'bebasLabByRa' => (object)[],
            ]);
        }

        
        $lab = DaftarLab::with('alatLabs')->find($lab_id);

        if (!$lab) {
            return redirect()->route('dashboard')
                ->with('error', 'Lab tidak ditemukan.');
        }

        if ($lab->lab_type !== 'penelitian') {
            return redirect()->back()->with('error', 'Mahasiswa hanya dapat meminjam alat dari lab penelitian.');
        }

        if (!$lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'disetujui_final'])
            ->get();

        
        foreach ($riskAssessments as $ra) {
            if (!$ra->id_ra) {
                $ra->generateIdRa();
            }
        }

        
        $validRiskAssessments = $riskAssessments->filter(function ($ra) {
            return $ra->isMasihBerlaku();
        });

        $riskAssessment = $validRiskAssessments->sortByDesc('batas_waktu_peminjaman')->first()
            ?? $riskAssessments->sortByDesc('batas_waktu_peminjaman')->first();

        
        $masihBerlaku = false;
        $pesanBatasWaktu = null;
        $sisaWaktu = null;

        if ($riskAssessment) {
            
            $masihBerlaku = $validRiskAssessments->isNotEmpty();

            if (!$masihBerlaku) {
                $pesanBatasWaktu = 'Batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir. '
                    . 'Terakhir bisa mengajukan: ' . $riskAssessment->getBatasWaktuPeminjamanFormatted()
                    . '. Silakan hubungi Kaprodi untuk perpanjangan.';
            }
            else {
                $sisaWaktu = $riskAssessment->getSisaWaktuPeminjaman();

                
                if ($riskAssessment->isHampirExpired()) {
                    $pesanBatasWaktu = 'Perhatian: Batas waktu pengajuan peminjaman akan berakhir dalam ' . $sisaWaktu
                        . '. Segera ajukan peminjaman jika Anda membutuhkan alat.';
                }
            }
        }
        else {
            $pesanBatasWaktu = 'Anda belum memiliki Risk Assessment yang disetujui untuk laboratorium ini. '
                . 'Silakan buat Risk Assessment terlebih dahulu sebelum mengajukan peminjaman alat.';
        }

        
        $peminjaman_alats = PeminjamanAlat::with('alatLab', 'riskAssessment')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        
        
        $bebasLabByRa = (object)BebasLabRequest::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('id', 'risk_assessment_id')
            ->toArray();

        return view('mahasiswa.pinjam-alat', compact(
            'lab',
            'labs',
            'user',
            'peminjaman_alats',
            'riskAssessment',
            'riskAssessments',
            'masihBerlaku',
            'pesanBatasWaktu',
            'sisaWaktu',
            'bebasLabByRa'
        ));
    }

    /**
     * Proses submit peminjaman
     */
    public function store(Request $request, $lab_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        
        $request->validate([
            'risk_assessment_id' => 'required|exists:risk_assessments,id',
            'alat_lab_id' => 'required|exists:alat_labs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ], [
            'risk_assessment_id.required' => 'Risk Assessment harus dipilih',
            'risk_assessment_id.exists' => 'Risk Assessment tidak valid',
            'alat_lab_id.required' => 'Alat harus dipilih',
            'alat_lab_id.exists' => 'Alat tidak valid',
            'jumlah.required' => 'Jumlah alat harus diisi',
            'jumlah.min' => 'Jumlah alat minimal 1',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal pinjam',
        ]);

        
        $lab = DaftarLab::findOrFail($lab_id);
        if (!$lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        if ($lab->lab_type !== 'penelitian') {
            return back()->with('error', 'Mahasiswa hanya dapat meminjam alat dari lab penelitian.');
        }

        $riskAssessment = RiskAssessment::where('id', $request->risk_assessment_id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'disetujui_final'])
            ->first();

        if (!$riskAssessment) {
            return back()->with('error', 'Risk Assessment tidak valid atau belum disetujui.');
        }

        
        $raLab = $riskAssessment->daftarLab;
        if ($raLab->stock_group_id != $lab->stock_group_id) {
            return back()->with('error', 'Risk Assessment tersebut diajukan untuk Lab ' . $raLab->floor . '.');
        }

        
        if (!$riskAssessment->isMasihBerlaku()) {
            return back()->with('error',
                'Maaf, batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir ('
                . $riskAssessment->getBatasWaktuPeminjamanFormatted()
                . '). Silakan hubungi Kaprodi untuk perpanjangan batas waktu.'
            );
        }

        
        if ($riskAssessment->batas_waktu_peminjaman &&
        Carbon::parse($riskAssessment->batas_waktu_peminjaman)->isPast()) {
            return back()->with('error',
                'Maaf, batas waktu untuk peminjaman alat sudah berakhir. '
                . 'Deadline peminjaman: ' . Carbon::parse($riskAssessment->batas_waktu_peminjaman)->format('d F Y')
                . '. Silakan hubungi Laboran untuk perpanjangan deadline atau ajukan peminjaman baru.'
            );
        }

        $alat = AlatLab::findOrFail($request->alat_lab_id);

        if ((int)$alat->stock_group_id !== (int)$lab->stock_group_id) {
            return back()->with('error', 'Alat tidak valid untuk grup stok laboratorium ini.');
        }

        
        if ($alat->daftar_lab_id && (int)$alat->daftar_lab_id !== (int)$lab->id) {
            return back()->with('error', 'Alat ini hanya tersedia di ' . $alat->daftarLab->Nama_Laboratorium . '.');
        }

        
        if ($alat->jumlah_tersedia < $request->jumlah) {
            return back()->with('error',
                'Maaf, stok alat "' . $alat->nama_alat . '" tidak mencukupi!'
            );
        }

        \DB::beginTransaction();
        try {
            
            $activeBebasLab = BebasLabRequest::where('user_id', $user->id)
                ->where('risk_assessment_id', $request->risk_assessment_id)
                ->where('is_active', true)
                ->first();

            if ($activeBebasLab) {
                $activeBebasLab->update([
                    'is_active' => false,
                    'status' => 'dibatalkan',
                ]);
            }

            
            $peminjaman = PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'risk_assessment_id' => $request->risk_assessment_id,
                'alat_lab_id' => $request->alat_lab_id,
                'daftar_lab_id' => $lab_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
            ]);

            
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $lab_id,
                'jenis_aktivitas' => 'Peminjaman Alat',
                'keterangan' => 'Mengajukan peminjaman: ' . $alat->nama_alat
                . ' (RA: ' . $riskAssessment->id_ra . ', Pinjam: ' . Carbon::parse($request->tanggal_pinjam)->format('d M Y') . ')',
                'waktu' => now(),
            ]);

            
            $laborans = DaftarUser::laboranForLab($lab)->get();

            foreach ($laborans as $laboran) {
                $recipientEmail = $laboran->getNotificationEmail();
                if ($recipientEmail) {
                    try {
                        Mail::to($recipientEmail)->send(
                            new PeminjamanAlatMail($peminjaman->load('alatLab.daftarLab', 'riskAssessment'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman alat berhasil dikirim ke laboran: ' . $recipientEmail);
                    }
                    catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke laboran: ' . $e->getMessage());
                    }
                }
            }

            \DB::commit();

            return redirect()->route('mahasiswa.aktivitas', $lab_id)
                ->with('success',
                'Peminjaman alat "' . $alat->nama_alat . '" berhasil diajukan dengan Risk Assessment ' . $riskAssessment->id_ra . '! '
                . 'Email notifikasi telah dikirim ke laboran. Menunggu persetujuan.'
            );

        }
        catch (\Exception $e) {
            \DB::rollBack();

            return back()->with('error',
                'Gagal mengajukan peminjaman: ' . $e->getMessage()
            );
        }
    }

    /**
     * Cek status peminjaman untuk mahasiswa
     */
    public function checkStatus()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $labs = DaftarLab::all();

        
        $peminjaman_alats = PeminjamanAlat::with(['alatLab.daftarLab'])
            ->where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.peminjaman-alat.status', compact('user', 'labs', 'peminjaman_alats'));
    }

    /**
     * Ajukan pengembalian alat oleh mahasiswa
     */
    public function ajukanPengembalian(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanAlat::findOrFail($id);

        
        if ($peminjaman->user_nama !== $user->Nama) {
            return back()->with('error', 'Anda tidak berhak mengajukan pengembalian untuk peminjaman ini.');
        }

        
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa dikembalikan.');
        }

        
        if ($peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Anda sudah mengajukan pengembalian untuk alat ini.');
        }

        $request->validate([
            'keterangan_pengembalian' => 'nullable|string|max:500',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
        ], [
            'kondisi_barang.required' => 'Kondisi barang harus dipilih',
            'kondisi_barang.in' => 'Kondisi barang tidak valid',
        ]);

        \DB::beginTransaction();
        try {
            
            $peminjaman->update([
                'pengajuan_pengembalian' => true,
                'tanggal_pengajuan_pengembalian' => now(),
                'keterangan_pengembalian' => $request->keterangan_pengembalian,
                'kondisi_barang' => $request->kondisi_barang,
            ]);

            
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $peminjaman->alatLab->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Alat',
                'keterangan' => 'Mengajukan pengembalian: ' . $peminjaman->alatLab->nama_alat
                . ' (Kondisi: ' . ucfirst($request->kondisi_barang) . ')',
                'waktu' => now(),
            ]);

            \DB::commit();

            return back()->with('success',
                'Pengajuan pengembalian alat "' . $peminjaman->alatLab->nama_alat . '" berhasil diajukan! '
                . 'Menunggu verifikasi dari laboran.'
            );

        }
        catch (\Exception $e) {
            \DB::rollBack();

            return back()->with('error',
                'Gagal mengajukan pengembalian: ' . $e->getMessage()
            );
        }
    }
}
