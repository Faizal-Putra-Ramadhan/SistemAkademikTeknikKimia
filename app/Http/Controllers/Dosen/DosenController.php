<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DaftarLab;
use App\Models\AlatLab;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanAlat;
use App\Models\PengajuanPenelitian;
use App\Models\Pengumuman;
use App\Models\ActivityLog;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    /**
     * Dashboard Dosen
     */
    // public function dashboard()
    // {
    //     $user = Auth::user();
        
    //     // Ambil pengajuan penelitian yang perlu disetujui dosen
    //     $pengajuanPenelitian = PengajuanPenelitian::where('dosen_pembimbing', $user->Nama)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
        
    //     // Statistik
    //     $totalPengajuan = $pengajuanPenelitian->count();
    //     $menungguPersetujuan = $pengajuanPenelitian->where('status', 'menunggu')->count();
    //     $disetujui = $pengajuanPenelitian->where('status', 'disetujui')->count();
    //     $ditolak = $pengajuanPenelitian->where('status', 'ditolak')->count();
        
    //     // Peminjaman dosen sendiri
    //     $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();
        
    //     $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
    //         ->with('alatLab')
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();
        
    //     return view('dosen.dashboard', compact(
    //         'user',
    //         'pengajuanPenelitian',
    //         'totalPengajuan',
    //         'menungguPersetujuan',
    //         'disetujui',
    //         'ditolak',
    //         'peminjamanRuangan',
    //         'peminjamanAlat'
    //     ));
    // }

    public function dashboard()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $riskAssessments = RiskAssessment::where('dosen_pembimbing_id', $user->id)
        ->with('daftarLab')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        
        // Ambil pengajuan penelitian yang perlu disetujui dosen
        // $pengajuanPenelitian = PengajuanPenelitian::where('dosen_pembimbing', $user->Nama)
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        
        // Statistik
        // $totalPengajuan = $pengajuanPenelitian->count();
        // $menungguPersetujuan = $pengajuanPenelitian->where('status', 'menunggu')->count();
        // $disetujui = $pengajuanPenelitian->where('status', 'disetujui')->count();
        // $ditolak = $pengajuanPenelitian->where('status', 'ditolak')->count();
        
        // Peminjaman dosen sendiri
        $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
            ->with('alatLab')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('dosen.dashboard', compact(
            'user',
            // 'pengajuanPenelitian',
            // 'totalPengajuan',
            // 'menungguPersetujuan',
            // 'disetujui',
            // 'ditolak',
            'peminjamanRuangan',
            'peminjamanAlat',
            'labs',
            'riskAssessments'
        ));
    }

    /**
     * Detail Pengajuan Penelitian
     */
    // public function detailPengajuanPenelitian($id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::with('daftarLab')->findOrFail($id);
        
    //     // Pastikan dosen ini adalah pembimbing dari penelitian ini
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         abort(403, 'Anda bukan pembimbing dari penelitian ini.');
    //     }
        
    //     return view('dosen.detail-pengajuan', compact('pengajuan', 'user'));
    // }

    // public function detailPengajuanPenelitian($id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::with('daftarLab')->findOrFail($id);
    //     $labs = DaftarLab::all();
        
    //     // Pastikan dosen ini adalah pembimbing dari penelitian ini
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         abort(403, 'Anda bukan pembimbing dari penelitian ini.');
    //     }
        
    //     return view('dosen.detail-pengajuan', compact('pengajuan', 'user', 'labs'));
    // }

    /**
     * Setujui Pengajuan Penelitian
     */
    // public function setujuiPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);
        
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }
        
    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'disetujui']);
            
    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menyetujui Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);
            
    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian berhasil disetujui!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
    //     }
    // }

    // public function setujuiPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);
        
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }
        
    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'disetujui']);
            
    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menyetujui Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);
            
    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian berhasil disetujui!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
    //     }
    // }

    /**
     * Tolak Pengajuan Penelitian
     */
    // public function tolakPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);
        
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }
        
    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'ditolak']);
            
    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menolak Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);
            
    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian ditolak.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
    //     }
    // }

    // public function tolakPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);
        
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }
        
    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'ditolak']);
            
    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menolak Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);
            
    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian ditolak.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
    //     }
    // }

    /**
     * Daftar Lab untuk Peminjaman
     */
    public function daftarLab()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        return view('dosen.daftar-lab', compact('labs', 'user'));
    }

    /**
     * Form Peminjaman Ruangan
     */
    // public function formPeminjamanRuangan($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();
        
    //     return view('dosen.pinjam-ruangan', compact('lab', 'user'));
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
        
        return view('dosen.pinjam-ruangan', compact('lab', 'user', 'labs', 'peminjaman_ruangans'));
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
    //         PeminjamanRuangan::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'tanggal' => $request->tanggal,
    //             'jam_mulai' => $request->jam_mulai,
    //             'jam_selesai' => $request->jam_selesai,
    //             'keperluan' => $request->keperluan,
    //             'status' => 'menunggu',
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Mengajukan Peminjaman Ruangan',
    //             'description' => "Lab: {$lab->Nama_Laboratorium}, Tanggal: {$request->tanggal}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.dashboard')
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

        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);

        DB::beginTransaction();
        try {
            PeminjamanRuangan::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengajukan Peminjaman Ruangan',
                'description' => "Lab: {$lab->Nama_Laboratorium}, Tanggal: {$request->tanggal}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('dosen.dashboard')
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
        
    //     return view('dosen.pinjam-alat', compact('lab', 'user'));
    // }

    public function formPeminjamanAlat($labId)
    {
        $lab = DaftarLab::with('alatLabs')->findOrFail($labId);
        $user = Auth::user();
        $labs = DaftarLab::all();
        $peminjaman_alats = PeminjamanAlat::with('alatLab')
    ->where('user_nama', $user->Nama)
    ->latest()
    ->get();
        
        return view('dosen.pinjam-alat', compact('lab', 'user', 'labs', 'peminjaman_alats'));
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

    //     if ($alat->jumlah_tersedia <= 0) {
    //         return back()->with('error', 'Alat tidak tersedia!');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         PeminjamanAlat::create([
    //             'user_nama' => $user->Nama,
    //             'alat_lab_id' => $request->alat_lab_id,
    //             'tanggal_pinjam' => $request->tanggal_pinjam,
    //             'tanggal_kembali' => $request->tanggal_kembali,
    //             'status' => 'menunggu',
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Mengajukan Peminjaman Alat',
    //             'description' => "Alat: {$alat->nama_alat}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.dashboard')
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

        if ($alat->jumlah_tersedia <= 0) {
            return back()->with('error', 'Alat tidak tersedia!');
        }

        DB::beginTransaction();
        try {
            PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'alat_lab_id' => $request->alat_lab_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengajukan Peminjaman Alat',
                'description' => "Alat: {$alat->nama_alat}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('dosen.dashboard')
                ->with('success', 'Peminjaman alat berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Daftar Pengumuman
     */
    // public function pengumuman()
    // {
    //     $user = Auth::user();
    //     $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        
    //     return view('dosen.pengumuman.index', compact('pengumuman', 'user'));
    // }

    

    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();
        
        return view('dosen.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }

    /**
     * Form Tambah Pengumuman
     */
    // public function createPengumuman()
    // {
    //     $user = Auth::user();
    //     return view('dosen.pengumuman.create', compact('user'));
    // }

    public function createPengumuman()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        return view('dosen.pengumuman.create', compact('user', 'labs'));
    }
    /**
     * Simpan Pengumuman
     */
    // public function storePengumuman(Request $request)
    // {
    //     $request->validate([
    //         'judul' => 'required|string|max:255',
    //         'isi' => 'required|string',
    //         'status' => 'required|in:draft,publish',
    //     ]);

    //     $user = Auth::user();

    //     DB::beginTransaction();
    //     try {
    //         Pengumuman::create([
    //             'judul' => $request->judul,
    //             'isi' => $request->isi,
    //             'status' => $request->status,
    //             'author' => $user->Nama,
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Membuat Pengumuman',
    //             'description' => "Judul: {$request->judul}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.pengumuman.index')
    //             ->with('success', 'Pengumuman berhasil dibuat!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal membuat pengumuman: ' . $e->getMessage());
    //     }
    // }

    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:draft,publish',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'status' => $request->status,
                'author' => $user->Nama,
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Membuat Pengumuman',
                'description' => "Judul: {$request->judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('dosen.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pengumuman: ' . $e->getMessage());
        }
    }

    /**
     * Form Edit Pengumuman
     */
    // public function editPengumuman($id)
    // {
    //     $user = Auth::user();
    //     $pengumuman = Pengumuman::findOrFail($id);
        
        
    //     // Hanya bisa edit pengumuman sendiri
    //     if ($pengumuman->author !== $user->Nama) {
    //         abort(403, 'Anda tidak dapat mengedit pengumuman orang lain.');
    //     }
        
    //     return view('dosen.pengumuman.edit', compact('pengumuman', 'user'));
    // }

    public function editPengumuman($id)
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::findOrFail($id);
        $labs = DaftarLab::all();
        
        // Hanya bisa edit pengumuman sendiri
        if ($pengumuman->author !== $user->Nama) {
            abort(403, 'Anda tidak dapat mengedit pengumuman orang lain.');
        }
        
        return view('dosen.pengumuman.edit', compact('pengumuman', 'user', 'labs'));
    }

    /**
     * Update Pengumuman
     */
    // public function updatePengumuman(Request $request, $id)
    // {
    //     $request->validate([
    //         'judul' => 'required|string|max:255',
    //         'isi' => 'required|string',
    //         'status' => 'required|in:draft,publish',
    //     ]);

    //     $user = Auth::user();
    //     $pengumuman = Pengumuman::findOrFail($id);
        
    //     if ($pengumuman->author !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak dapat mengedit pengumuman orang lain.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $pengumuman->update([
    //             'judul' => $request->judul,
    //             'isi' => $request->isi,
    //             'status' => $request->status,
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Mengupdate Pengumuman',
    //             'description' => "Judul: {$request->judul}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.pengumuman.index')
    //             ->with('success', 'Pengumuman berhasil diupdate!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengupdate pengumuman: ' . $e->getMessage());
    //     }
    // }

    public function updatePengumuman(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:draft,publish',
        ]);

        $user = Auth::user();
        $pengumuman = Pengumuman::findOrFail($id);
        
        if ($pengumuman->author !== $user->Nama) {
            return back()->with('error', 'Anda tidak dapat mengedit pengumuman orang lain.');
        }

        DB::beginTransaction();
        try {
            $pengumuman->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'status' => $request->status,
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengupdate Pengumuman',
                'description' => "Judul: {$request->judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('dosen.pengumuman.index')
                ->with('success', 'Pengumuman berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate pengumuman: ' . $e->getMessage());
        }
    }


    /**
     * Hapus Pengumuman
     */
    // public function destroyPengumuman($id)
    // {
    //     $user = Auth::user();
    //     $pengumuman = Pengumuman::findOrFail($id);
        
    //     if ($pengumuman->author !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak dapat menghapus pengumuman orang lain.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $judul = $pengumuman->judul;
    //         $pengumuman->delete();

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menghapus Pengumuman',
    //             'description' => "Judul: {$judul}",
    //             'ip_address' => request()->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.pengumuman.index')
    //             ->with('success', 'Pengumuman berhasil dihapus!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
    //     }
    // }

    public function destroyPengumuman($id)
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::findOrFail($id);
        
        if ($pengumuman->author !== $user->Nama) {
            return back()->with('error', 'Anda tidak dapat menghapus pengumuman orang lain.');
        }

        DB::beginTransaction();
        try {
            $judul = $pengumuman->judul;
            $pengumuman->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Menghapus Pengumuman',
                'description' => "Judul: {$judul}",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();
            return redirect()->route('dosen.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }

    /**
     * Profil Dosen
     */
    // public function profil()
    // {
    //     $user = Auth::user();
    //     return view('dosen.profil', compact('user'));
    // }

    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        return view('dosen.profil', compact('user', 'labs'));
    }

    /**
     * Update Profil
     */
    // public function updateProfil(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'Nama' => 'required|string|max:255',
    //         'Phone' => 'required|string|max:20',
    //         'Email' => 'required|email|unique:daftar_users,Email,' . $user->id,
    //         'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $data = [
    //             'Nama' => $request->Nama,
    //             'Phone' => $request->Phone,
    //             'Email' => $request->Email,
    //         ];

    //         // Upload foto jika ada
    //         if ($request->hasFile('foto')) {
    //             $file = $request->file('foto');
    //             $filename = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('uploads/profile'), $filename);
    //             $data['foto'] = $filename;

    //             // Hapus foto lama jika ada
    //             if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
    //                 unlink(public_path('uploads/profile/' . $user->foto));
    //             }
    //         }

    //         $user->update($data);

    //         DB::commit();
    //         return back()->with('success', 'Profil berhasil diupdate!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
    //     }
    // }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Phone' => 'required|string|max:20',
            'Email' => 'required|email|unique:daftar_users,Email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'Nama' => $request->Nama,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $data['foto'] = $filename;

                // Hapus foto lama jika ada
                if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
                    unlink(public_path('uploads/profile/' . $user->foto));
                }
            }

            $user->update($data);

            DB::commit();
            return back()->with('success', 'Profil berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
        }
    }
}