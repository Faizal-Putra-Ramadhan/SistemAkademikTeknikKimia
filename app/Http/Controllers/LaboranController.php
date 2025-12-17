<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarLab;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanAlat;
use App\Models\PengajuanPenelitian;
use App\Models\Pengumuman;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LaboranController extends Controller
{
    // public function dashboard()
    // {
    //     $user = Auth::user();
        
    //     // Ambil data laboran berdasarkan user yang login
    //     $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        
    //     if (!$laboran) {
    //         return redirect()->route('login')->with('error', 'Data laboran tidak ditemukan');
    //     }
        
    //     // Ambil daftar lab yang dikelola laboran ini
    //     $daftarLab = \App\Models\DaftarLab::where('Nama_Laboratorium', $laboran->Laboratorium)->first();
        
    //     if (!$daftarLab) {
    //         return redirect()->route('login')->with('error', 'Laboratorium tidak ditemukan');
    //     }
        
    //     // Statistik
    //     $peminjamanRuanganMenunggu = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
    //         ->where('status', 'menunggu')
    //         ->count();
            
    //     $peminjamanAlatMenunggu = PeminjamanAlat::whereHas('alatLab', function($query) use ($daftarLab) {
    //         $query->where('daftar_lab_id', $daftarLab->id);
    //     })->where('status', 'menunggu')->count();
        
    //     $pengajuanPenelitianMenunggu = PengajuanPenelitian::where('daftar_lab_id', $daftarLab->id)
    //         ->where('status', 'menunggu')
    //         ->count();
            
    //     $totalPengumuman = Pengumuman::where('author', $user->Nama)
    //         ->where('status', 'publish')
    //         ->count();
        
    //     // Data untuk tabel
    //     $peminjamanRuangan = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
            
    //     $peminjamanAlat = PeminjamanAlat::whereHas('alatLab', function($query) use ($daftarLab) {
    //         $query->where('daftar_lab_id', $daftarLab->id);
    //     })->with('alatLab')->orderBy('created_at', 'desc')->get();
        
    //     $pengajuanPenelitian = PengajuanPenelitian::where('daftar_lab_id', $daftarLab->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
            
    //     $pengumuman = Pengumuman::where('author', $user->Nama)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
        
    //     return view('laboran.dashboard', compact(
    //         'laboran',
    //         'peminjamanRuanganMenunggu',
    //         'peminjamanAlatMenunggu',
    //         'pengajuanPenelitianMenunggu',
    //         'totalPengumuman',
    //         'peminjamanRuangan',
    //         'peminjamanAlat',
    //         'pengajuanPenelitian',
    //         'pengumuman'
    //     ));
    // }

    public function dashboard()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        // Ambil data laboran berdasarkan user yang login
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        
    
        
        // Ambil daftar lab yang dikelola laboran ini
        $daftarLab = \App\Models\DaftarLab::where('Nama_Laboratorium', $laboran->Laboratorium)->first();
        
        if (!$daftarLab) {
            return redirect()->route('login')->with('error', 'Laboratorium tidak ditemukan');
        }
        
        // Statistik
        $peminjamanRuanganMenunggu = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
            ->where('status', 'menunggu')
            ->count();
            
        $peminjamanAlatMenunggu = PeminjamanAlat::whereHas('alatLab', function($query) use ($daftarLab) {
            $query->where('daftar_lab_id', $daftarLab->id);
        })->where('status', 'menunggu')->count();
        
        // $pengajuanPenelitianMenunggu = PengajuanPenelitian::where('daftar_lab_id', $daftarLab->id)
        //     ->where('status', 'menunggu')
        //     ->count();
            
        $totalPengumuman = Pengumuman::where('author', $user->Nama)
            ->where('status', 'publish')
            ->count();
        
        // Data untuk tabel
        $peminjamanRuangan = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $peminjamanAlat = PeminjamanAlat::whereHas('alatLab', function($query) use ($daftarLab) {
            $query->where('daftar_lab_id', $daftarLab->id);
        })->with('alatLab')->orderBy('created_at', 'desc')->get();
        
        // $pengajuanPenelitian = PengajuanPenelitian::where('daftar_lab_id', $daftarLab->id)
        //     ->orderBy('created_at', 'desc')
        //     ->get();
            
        $pengumuman = Pengumuman::where('author', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('laboran.dashboard', compact(
            'laboran',
            'peminjamanRuanganMenunggu',
            'peminjamanAlatMenunggu',
            
            'totalPengumuman',
            'peminjamanRuangan',
            'peminjamanAlat',
           
            'pengumuman',
            'user',
            'labs'
        ));
    }
    /**
     * Halaman Peminjaman Ruangan
     */
    public function peminjamanRuangan($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

        // PERBAIKAN: Gunakan daftar_lab_id sesuai struktur database
        $peminjamanRuangan = PeminjamanRuangan::where('daftar_lab_id', $lab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $labs = DaftarLab::where('id', $id)->get();

        return view('laboran.peminjaman-ruangan', compact(
            'user',
            'laboran',
            'labs',
            'peminjamanRuangan'
        ));
    }

    /**
     * Halaman Peminjaman Alat
     */
    public function peminjamanAlat($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

        // PERBAIKAN: Query melalui relasi alatLab dengan benar
        $peminjamanAlat = PeminjamanAlat::with('alatLab')
            ->whereHas('alatLab', function($query) use ($lab) {
                $query->where('daftar_lab_id', $lab->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $labs = DaftarLab::where('id', $id)->get();

        return view('laboran.peminjaman-alat', compact(
            'user',
            'laboran',
            'labs',
            'peminjamanAlat'
        ));
    }

    /**
     * Halaman Pengajuan Penelitian
     */
    // public function pengajuanPenelitian($id)
    // {
    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($id);
    //     $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

    //     // PERBAIKAN: Gunakan daftar_lab_id
    //     $pengajuanPenelitian = PengajuanPenelitian::where('daftar_lab_id', $lab->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $labs = DaftarLab::where('id', $id)->get();

    //     return view('laboran.pengajuan-penelitian', compact(
    //         'user',
    //         'laboran',
    //         'labs',
    //         'pengajuanPenelitian'
    //     ));
    // }

    /**
     * Halaman Kelola Pengumuman
     */
    public function pengumuman($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

        // Ambil semua pengumuman untuk author ini
        $pengumuman = Pengumuman::where('author', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        $labs = DaftarLab::where('id', $id)->get();

        return view('laboran.kelola-pengumuman', compact(
            'user',
            'laboran',
            'labs',
            'pengumuman'
        ));
    }

    /**
     * Setujui Peminjaman Ruangan
     */
    public function setujuiRuangan($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menyetujui Peminjaman Ruangan',
            'description' => "Peminjaman ruangan oleh {$peminjaman->user_nama} telah disetujui",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil disetujui');
    }

    /**
     * Tolak Peminjaman Ruangan
     */
    public function tolakRuangan($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menolak Peminjaman Ruangan',
            'description' => "Peminjaman ruangan oleh {$peminjaman->user_nama} telah ditolak",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Peminjaman ruangan ditolak');
    }

    /**
     * Setujui Peminjaman Alat
     */
    public function setujuiAlat($id)
    {
        $peminjaman = PeminjamanAlat::findOrFail($id);
        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menyetujui Peminjaman Alat',
            'description' => "Peminjaman alat oleh {$peminjaman->user_nama} telah disetujui",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Peminjaman alat berhasil disetujui');
    }

    /**
     * Tolak Peminjaman Alat
     */
    public function tolakAlat($id)
    {
        $peminjaman = PeminjamanAlat::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menolak Peminjaman Alat',
            'description' => "Peminjaman alat oleh {$peminjaman->user_nama} telah ditolak",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Peminjaman alat ditolak');
    }

    /**
     * Tandai Alat Dikembalikan
     */
    public function kembalikanAlat($id)
    {
        $peminjaman = PeminjamanAlat::findOrFail($id);
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menandai Alat Dikembalikan',
            'description' => "Alat dari {$peminjaman->user_nama} telah dikembalikan",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Alat berhasil ditandai dikembalikan');
    }

    /**
     * Detail Pengajuan Penelitian
     */
    public function detailPenelitian($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        $user = Auth::user();
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        $labs = DaftarLab::where('id', $penelitian->daftar_lab_id)->get();

        return view('laboran.detail-penelitian', compact('penelitian', 'user', 'laboran', 'labs'));
    }

    /**
     * Setujui Pengajuan Penelitian
     */
    public function setujuiPenelitian($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        $penelitian->status = 'disetujui';
        $penelitian->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menyetujui Pengajuan Penelitian',
            'description' => "Pengajuan penelitian '{$penelitian->judul_penelitian}' oleh {$penelitian->user_nama} telah disetujui",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Pengajuan penelitian berhasil disetujui');
    }

    /**
     * Tolak Pengajuan Penelitian
     */
    public function tolakPenelitian($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        $penelitian->status = 'ditolak';
        $penelitian->save();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menolak Pengajuan Penelitian',
            'description' => "Pengajuan penelitian '{$penelitian->judul_penelitian}' oleh {$penelitian->user_nama} telah ditolak",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Pengajuan penelitian ditolak');
    }

    /**
     * Form Create Pengumuman
     */
    public function createPengumuman()
    {
        $user = Auth::user();
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        $labs = DaftarLab::all();

        return view('laboran.create-pengumuman', compact('user', 'laboran', 'labs'));
    }

    /**
     * Store Pengumuman
     */
    public function storePengumuman(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:publish,draft'
        ]);

        $validated['author'] = Auth::user()->Nama;

        Pengumuman::create($validated);

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Membuat Pengumuman',
            'description' => "Pengumuman '{$request->judul}' telah dibuat",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dibuat');
    }

    /**
     * Form Edit Pengumuman
     */
    public function editPengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $user = Auth::user();
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        $labs = DaftarLab::all();

        return view('laboran.edit-pengumuman', compact('pengumuman', 'user', 'laboran', 'labs'));
    }

    /**
     * Update Pengumuman
     */
    public function updatePengumuman(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'status' => 'required|in:publish,draft'
        ]);

        $pengumuman->update($validated);

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Mengupdate Pengumuman',
            'description' => "Pengumuman '{$request->judul}' telah diupdate",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil diupdate');
    }

    /**
     * Delete Pengumuman
     */
    public function destroyPengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $judul = $pengumuman->judul;
        $pengumuman->delete();

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menghapus Pengumuman',
            'description' => "Pengumuman '{$judul}' telah dihapus",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus');
    }

    // Tambahkan method ini ke LaboranController.php

/**
 * Halaman Risk Assessment (Read-Only untuk Laboran)
 */
public function riskAssessment($id)
{
    $user = Auth::user();
    $lab = DaftarLab::findOrFail($id);
    $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

    // Ambil semua Risk Assessment untuk lab ini
    $riskAssessments = \App\Models\RiskAssessment::with([
        'user',
        'daftarLab',
        'dosenPembimbing',
        'safetyOfficer',
        'kepalaLab',
        'bahanKimias',
        'peralatanOperasi',
        'pelakuKerja',
        'kategoriHazardBahan',
        'pernyataanMahasiswa'
    ])
    ->where('daftar_lab_id', $lab->id)
    ->orderBy('created_at', 'desc')
    ->get();

    $labs = DaftarLab::where('id', $id)->get();

    return view('laboran.risk-assessment', compact(
        'user',
        'laboran',
        'labs',
        'riskAssessments',
        'lab'
    ));
}

/**
 * Detail Risk Assessment (Read-Only untuk Laboran)
 */
public function detailRiskAssessment($id)
{
    $user = Auth::user();
    $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

    $riskAssessment = \App\Models\RiskAssessment::with([
        'user',
        'daftarLab',
        'dosenPembimbing',
        'safetyOfficer',
        'kepalaLab',
        'bahanKimias',
        'peralatanOperasi',
        'pelakuKerja',
        'kategoriHazardBahan',
        'pernyataanMahasiswa'
    ])->findOrFail($id);

    $labs = DaftarLab::where('id', $riskAssessment->daftar_lab_id)->get();

    return view('laboran.detail-risk-assessment', compact(
        'user',
        'laboran',
        'labs',
        'riskAssessment'
    ));
}
}