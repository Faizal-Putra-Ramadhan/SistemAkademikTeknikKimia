<?php

namespace App\Http\Controllers\KepalaLab;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KepalaLabController extends Controller
{
    /**
     * Tampilkan daftar Risk Assessment yang menunggu persetujuan Kepala Lab
     */
    public function index()
    {
        $user = Auth::user();
         $labs = DaftarLab::all();
        
        $riskAssessments = RiskAssessment::with([
            'user', 
            'daftarLab', 
            'dosenPembimbing',
            'safetyOfficer'
        ])
            ->where('status', 'menunggu_kepala_lab')
            ->latest()
            ->paginate(10);

        $riwayat = RiskAssessment::with([
            'user', 
            'daftarLab', 
            'dosenPembimbing',
            'safetyOfficer'
        ])
            ->where('kepala_lab_id', $user->id)
            ->whereNotIn('status', ['menunggu_kepala_lab'])
            ->latest()
            ->paginate(10);

        return view('kepala-lab.risk-assessment.index', compact('riskAssessments', 'riwayat', 'user', 'labs'));
    }

    /**
     * Tampilkan detail Risk Assessment untuk final approval
     */
    public function show($id)
    {
         $labs = DaftarLab::all();
         $user = Auth::user();

        $riskAssessment = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'kepalaLab',
            'bahanKimias',
            'kategoriHazardBahan',
            'peralatanOperasi',
            'pelakuKerja',
            'pernyataanMahasiswa'
        ])->findOrFail($id);

        return view('kepala-lab.risk-assessment.review', compact('riskAssessment', 'labs', 'user'));
    }

    /**
     * Proses final approval dari Kepala Lab
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_kepala_lab') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses sebelumnya.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        $riskAssessment->update([
            'kepala_lab_id' => Auth::user()->id,
            'persetujuan_kepala_lab' => $disetujui,
            'catatan_kepala_lab' => $request->catatan,
            'tanggal_persetujuan_kepala_lab' => now(),
            'status' => $disetujui ? 'disetujui' : 'ditolak',
        ]);

        $message = $disetujui 
            ? 'Risk Assessment berhasil disetujui! Mahasiswa sudah dapat melakukan penelitian/praktikum.'
            : 'Risk Assessment ditolak.';

        return redirect()
            ->route('kepala-lab.risk-assessment.index')
            ->with('success', $message);
    }

    /**
     * Request revisi
     */
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        $riskAssessment->update([
            'kepala_lab_id' => Auth::user()->id,
            'catatan_kepala_lab' => $request->catatan_revisi,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('kepala-lab.risk-assessment.index')
            ->with('success', 'Permintaan revisi berhasil dikirim.');
    }

    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        $statistics = [
            'menunggu' => RiskAssessment::where('status', 'menunggu_kepala_lab')->count(),
            'disetujui_bulan_ini' => RiskAssessment::where('status', 'disetujui')
                ->whereMonth('tanggal_persetujuan_kepala_lab', now()->month)
                ->whereYear('tanggal_persetujuan_kepala_lab', now()->year)
                ->count(),
            'ditolak_bulan_ini' => RiskAssessment::where('status', 'ditolak')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
            'total_tahun_ini' => RiskAssessment::whereYear('created_at', now()->year)->count(),
        ];

        $recentApprovals = RiskAssessment::with(['user', 'daftarLab'])
            ->where('status', 'disetujui')
            ->latest('tanggal_persetujuan_kepala_lab')
            ->limit(5)
            ->get();

        return view('kepala-lab.dashboard', compact('statistics', 'recentApprovals', 'labs', 'user'));
    }

    /**
     * Laporan Risk Assessment
     */
    public function report(Request $request)
    {
        $query = RiskAssessment::with([
            'user', 
            'daftarLab', 
            'dosenPembimbing'
        ]);

        $user = Auth::user();
        $labs = DaftarLab::all();

        // Filter berdasarkan periode
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date, 
                $request->end_date
            ]);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan lab
        if ($request->has('lab_id') && $request->lab_id !== 'all') {
            $query->where('daftar_lab_id', $request->lab_id);
        }

        // Filter berdasarkan kategori resiko
        if ($request->has('kategori_resiko') && $request->kategori_resiko !== 'all') {
            $query->where('kategori_resiko_dosen', $request->kategori_resiko);
        }

        // Filter berdasarkan jenis RA
        if ($request->has('jenis_ra') && $request->jenis_ra !== 'all') {
            $query->where('jenis_ra', $request->jenis_ra);
        }

        $riskAssessments = $query->latest()->paginate(20);

        return view('kepala-lab.risk-assessment.report', compact('riskAssessments', 'user', 'labs'));
    }

    // Pengumuman
        public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();
        
        return view('kepala-lab.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }


    public function createPengumuman()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        return view('kepala-lab.pengumuman.create', compact('user', 'labs'));
    }
    

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
            return redirect()->route('kepala-lab.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pengumuman: ' . $e->getMessage());
        }
    }

    public function editPengumuman($id)
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::findOrFail($id);
        $labs = DaftarLab::all();
        
        // Hanya bisa edit pengumuman sendiri
        if ($pengumuman->author !== $user->Nama) {
            abort(403, 'Anda tidak dapat mengedit pengumuman orang lain.');
        }
        
        return view('kepala-lab.pengumuman.edit', compact('pengumuman', 'user', 'labs'));
    }

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
            return redirect()->route('kepala-lab.pengumuman.index')
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
    //         return redirect()->route('kepala-lab.pengumuman.index')
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
            return redirect()->route('kepala-lab.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }
}