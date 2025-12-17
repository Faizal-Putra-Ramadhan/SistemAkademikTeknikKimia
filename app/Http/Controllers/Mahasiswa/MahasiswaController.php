<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DaftarLab;
use App\Models\AlatLab;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanAlat;
use App\Models\PengajuanPenelitian;
use App\Models\AktivitasMahasiswa;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * Dashboard Mahasiswa - Menampilkan semua lab
     */
    // public function dashboard()
    // {
    //     $labs = DaftarLab::all();
    //     $user = Auth::user();
        
    //     return view('mahasiswa.dashboard', compact('labs', 'user'));
    // }

     public function dashboard()
    {
        $labs = DaftarLab::all();
        $user = Auth::user();
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('mahasiswa.dashboard', compact('labs', 'user', 'pengumuman'));
    }
    

    /**
     * Detail Lab - Menampilkan info lab dan alat yang tersedia
     */
    public function detailLab($id)
    {
        $lab = DaftarLab::with('alatLabs')->findOrFail($id);
        $user = Auth::user();
        
        return view('mahasiswa.detail-lab', compact('lab', 'user'));
    }

    /**
     * Form Peminjaman Ruangan
     */
    // public function formPeminjamanRuangan($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();
        
    //     return view('mahasiswa.pinjam-ruangan', compact('lab', 'user'));
    // }

    public function formPeminjamanRuangan($labId)
    {
        $lab = DaftarLab::findOrFail($labId);
        $user = Auth::user();
        $labs = DaftarLab::all();

        $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
    ->where('user_nama', $user->Nama)
    ->latest()
    ->get();
        
        return view('mahasiswa.pinjam-ruangan', compact('lab', 'user', 'labs', 'peminjaman_ruangans'));
    }

    /**
     * Simpan Peminjaman Ruangan
     */
    // public function storePeminjamanRuangan(Request $request, $labId)
    // {
    //     $request->validate([
    //         'tanggal' => 'required|date|after_or_equal:today',
    //         'jam_mulai' => 'required',
    //         'jam_selesai' => 'required|after:jam_mulai',
    //         'keperluan' => 'required|string|max:500',
    //     ]);

    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     DB::beginTransaction();
    //     try {
    //         // Simpan peminjaman ruangan
    //         $peminjaman = PeminjamanRuangan::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'tanggal' => $request->tanggal,
    //             'jam_mulai' => $request->jam_mulai,
    //             'jam_selesai' => $request->jam_selesai,
    //             'keperluan' => $request->keperluan,
    //             'status' => 'menunggu',
    //         ]);

    //         $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
    // ->where('user_nama', $user->Nama)
    // ->latest()
    // ->get();

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Peminjaman Ruangan',
    //             'keterangan' => "Mengajukan peminjaman ruangan {$lab->Nama_Laboratorium} pada {$request->tanggal}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
    //             ->with('success', 'Peminjaman ruangan berhasil diajukan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    //     }
    // }

    public function storePeminjamanRuangan(Request $request, $labId)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keperluan' => 'required|string|max:500',
        ]);

        $labs = DaftarLab::all();
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);

        DB::beginTransaction();
        try {
            // Simpan peminjaman ruangan
            $peminjaman = PeminjamanRuangan::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
            ]);

            // Catat aktivitas
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'jenis_aktivitas' => 'Peminjaman Ruangan',
                'keterangan' => "Mengajukan peminjaman ruangan {$lab->Nama_Laboratorium} pada {$request->tanggal}",
            ]);

            DB::commit();
            return redirect()->route('mahasiswa.aktivitas', $labId)
                ->with('success', 'Peminjaman ruangan berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Form Peminjaman Alat
     */
    // public function formPeminjamanAlat($labId)
    // {
    //     $lab = DaftarLab::with('alatLabs')->findOrFail($labId);
    //     $user = Auth::user();
        
    //     return view('mahasiswa.pinjam-alat', compact('lab', 'user'));
    // }

    public function formPeminjamanAlat($labId)
    {
        $labs = DaftarLab::all();
        $lab = DaftarLab::with('alatLabs')->findOrFail($labId);
        $user = Auth::user();

        $peminjaman_alats = PeminjamanAlat::with('alatLab')
    ->where('user_nama', $user->Nama)
    ->latest()
    ->get();
        
        return view('mahasiswa.pinjam-alat', compact('lab', 'user', 'labs', 'peminjaman_alats'));
    }

    /**
     * Simpan Peminjaman Alat
     */
    // public function storePeminjamanAlat(Request $request, $labId)
    // {
    //     $request->validate([
    //         'alat_lab_id' => 'required|exists:alat_labs,id',
    //         'tanggal_pinjam' => 'required|date|after_or_equal:today',
    //         'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
    //     ]);

    //     $user = Auth::user();
    //     $alat = AlatLab::findOrFail($request->alat_lab_id);
    //     $lab = DaftarLab::findOrFail($labId);

    //     // Cek ketersediaan alat
    //     if ($alat->jumlah_tersedia <= 0) {
    //         return back()->with('error', 'Alat tidak tersedia!');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Simpan peminjaman alat
    //         PeminjamanAlat::create([
    //             'user_nama' => $user->Nama,
    //             'alat_lab_id' => $request->alat_lab_id,
    //             'tanggal_pinjam' => $request->tanggal_pinjam,
    //             'tanggal_kembali' => $request->tanggal_kembali,
    //             'status' => 'menunggu',
    //         ]);

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Peminjaman Alat',
    //             'keterangan' => "Mengajukan peminjaman {$alat->nama_alat}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
    //             ->with('success', 'Peminjaman alat berhasil diajukan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    //     }
    // }

    public function storePeminjamanAlat(Request $request, $labId)
    {
        $request->validate([
            'alat_lab_id' => 'required|exists:alat_labs,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        $user = Auth::user();
        $alat = AlatLab::findOrFail($request->alat_lab_id);
        $lab = DaftarLab::findOrFail($labId);

        // Cek ketersediaan alat
        if ($alat->jumlah_tersedia <= 0) {
            return back()->with('error', 'Alat tidak tersedia!');
        }

        DB::beginTransaction();
        try {
            // Simpan peminjaman alat
            PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'alat_lab_id' => $request->alat_lab_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
            ]);

            // Catat aktivitas
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'jenis_aktivitas' => 'Peminjaman Alat',
                'keterangan' => "Mengajukan peminjaman {$alat->nama_alat}",
            ]);

            DB::commit();
            return redirect()->route('mahasiswa.aktivitas', $labId)
                ->with('success', 'Peminjaman alat berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Form Pengajuan Penelitian
     */
    // public function formPengajuanPenelitian($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();

    //     $penelitians = PengajuanPenelitian::with('daftarLab')
    // ->where('user_nama', $user->Nama)
    // ->latest()
    // ->get();
        
    //     return view('mahasiswa.pengajuan-penelitian', compact('lab', 'user'));
    // }

    // /**
    //  * Simpan Pengajuan Penelitian
    //  */
    // public function storePengajuanPenelitian(Request $request, $labId)
    // {
    //     $request->validate([
    //         'judul_penelitian' => 'required|string|max:255',
    //         'deskripsi' => 'required|string',
    //         'tanggal_mulai' => 'required|date|after_or_equal:today',
    //         'tanggal_selesai' => 'required|date|after:tanggal_mulai',
    //         'dosen_pembimbing' => 'required|string|max:255',
    //     ]);

    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     DB::beginTransaction();
    //     try {
    //         // Simpan pengajuan penelitian
    //         PengajuanPenelitian::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'judul_penelitian' => $request->judul_penelitian,
    //             'deskripsi' => $request->deskripsi,
    //             'tanggal_mulai' => $request->tanggal_mulai,
    //             'tanggal_selesai' => $request->tanggal_selesai,
    //             'dosen_pembimbing' => $request->dosen_pembimbing,
    //             'status' => 'menunggu',
    //         ]);

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Pengajuan Penelitian',
    //             'keterangan' => "Mengajukan penelitian: {$request->judul_penelitian}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
    //             ->with('success', 'Pengajuan penelitian berhasil dikirim!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan penelitian: ' . $e->getMessage());
    //     }
    // }

    /**
     * Aktivitas Mahasiswa
     */
    // public function aktivitas($labId)
    // {
    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     // Ambil semua aktivitas mahasiswa di lab ini
    //     $aktivitas = AktivitasMahasiswa::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('waktu', 'desc')
    //         ->get();

    //     // Ambil status peminjaman
    //     $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
    //         ->with('alatLab')
    //         ->whereHas('alatLab', function($query) use ($labId) {
    //             $query->where('daftar_lab_id', $labId);
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $pengajuanPenelitian = PengajuanPenelitian::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('mahasiswa.aktivitas', compact(
    //         'lab', 
    //         'user', 
    //         'aktivitas', 
    //         'peminjamanRuangan', 
    //         'peminjamanAlat', 
    //         'pengajuanPenelitian'
    //     ));
    // }

    public function aktivitas($labId)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);
        $labs = DaftarLab::all();

        // Ambil semua aktivitas mahasiswa di lab ini
        $aktivitas = AktivitasMahasiswa::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $labId)
            ->orderBy('waktu', 'desc')
            ->get();

        // Ambil status peminjaman
        $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $labId)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
            ->with('alatLab')
            ->whereHas('alatLab', function($query) use ($labId) {
                $query->where('daftar_lab_id', $labId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // $pengajuanPenelitian = PengajuanPenelitian::where('user_nama', $user->Nama)
        //     ->where('daftar_lab_id', $labId)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

         $riskAssessments = RiskAssessment::where('user_id', $user->id)
                ->where('daftar_lab_id', $labId)
                ->orderBy('created_at', 'desc')
                ->get();

        return view('mahasiswa.aktivitas', compact(
            'lab',
            'labs',
            'user', 
            'aktivitas', 
            'peminjamanRuangan', 
            'peminjamanAlat',
            'riskAssessments'
        ));
    }

    /**
     * Daftar Pengumuman
     */
    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pengumuman', compact('pengumuman', 'user'));
    }
}