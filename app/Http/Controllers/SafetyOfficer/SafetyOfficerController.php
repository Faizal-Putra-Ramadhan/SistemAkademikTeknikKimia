<?php

// ============================================================================
// FILE 1: app/Http/Controllers/SafetyOfficer/SafetyOfficerController.php
// ============================================================================

namespace App\Http\Controllers\SafetyOfficer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use App\Models\DaftarLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafetyOfficerController extends Controller
{
    /**
     * Dashboard Safety Officer
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil lab yang safety officer-nya adalah user ini
        $labs = DaftarLab::where('Safety_Officer', $user->Nama)->get();
        
        // Statistik
        $pending = RiskAssessment::where('status', 'menunggu_safety_officer')->count();
        $scheduled = RiskAssessment::where('safety_officer_id', $user->id)
            ->whereNotNull('jadwal_wawancara')
            ->where('jadwal_wawancara', '>=', now())
            ->count();
        $approved = RiskAssessment::where('safety_officer_id', $user->id)
            ->where('persetujuan_safety_officer', true)
            ->count();
        $rejected = RiskAssessment::where('safety_officer_id', $user->id)
            ->where('persetujuan_safety_officer', false)
            ->count();

        return view('safety-officer.dashboard', compact(
            'labs',
            'pending',
            'scheduled',
            'approved',
            'rejected', 
            'user'
        ));
    }

    /**
     * Tampilkan daftar Risk Assessment yang menunggu review Safety Officer
     */
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        // RA yang menunggu review
        $riskAssessments = RiskAssessment::with(['user', 'daftarLab', 'dosenPembimbing'])
            ->where('status', 'menunggu_safety_officer')
            ->latest()
            ->paginate(10);

        // Riwayat RA yang sudah di-review
        $riwayat = RiskAssessment::with(['user', 'daftarLab', 'dosenPembimbing'])
            ->where('safety_officer_id', $user->id)
            ->whereNotIn('status', ['menunggu_safety_officer'])
            ->latest()
            ->paginate(10);

        return view('safety-officer.risk-assessment.index', compact('riskAssessments', 'riwayat', 'labs', 'user'));
    }

    /**
     * Tampilkan detail Risk Assessment untuk review
     */
    public function show($id)
    {
        $riskAssessment = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'bahanKimias',
            'kategoriHazardBahan',
            'peralatanOperasi',
            'pelakuKerja',
            'pernyataanMahasiswa'
        ])->findOrFail($id);

        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('safety-officer.risk-assessment.show', compact('riskAssessment', 'labs', 'user'));
    }

    /**
     * Jadwalkan wawancara dengan mahasiswa
     */
    public function scheduleInterview(Request $request, $id)
    {
        $request->validate([
            'jadwal_wawancara' => 'required|date|after:now',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        $riskAssessment->update([
            'safety_officer_id' => Auth::user()->id,
            'safety_officer_nama' => Auth::user()->Nama,
            'jadwal_wawancara' => $request->jadwal_wawancara,
            'catatan_safety_officer' => $request->catatan,
        ]);

        return redirect()
            ->route('safety-officer.risk-assessment.show', $id)
            ->with('success', 'Jadwal wawancara berhasil ditentukan. Mahasiswa akan diberitahu.');
    }

    /**
     * Proses persetujuan Safety Officer
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        $riskAssessment->update([
            'safety_officer_id' => Auth::user()->id,
            'safety_officer_nama' => Auth::user()->Nama,
            'persetujuan_safety_officer' => $disetujui,
            'catatan_safety_officer' => $request->catatan,
            'tanggal_persetujuan_safety_officer' => now(),
            'status' => $disetujui ? 'menunggu_kepala_lab' : 'ditolak',
        ]);

        $message = $disetujui 
            ? 'Risk Assessment berhasil disetujui. Akan dilanjutkan ke Kepala Laboratorium.'
            : 'Risk Assessment ditolak.';

        return redirect()
            ->route('safety-officer.risk-assessment.index')
            ->with('success', $message);
    }

    /**
     * Request revisi dari mahasiswa
     */
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        $riskAssessment->update([
            'safety_officer_id' => Auth::user()->id,
            'safety_officer_nama' => Auth::user()->Nama,
            'catatan_safety_officer' => $request->catatan_revisi,
            'status' => 'draft', // Kembalikan ke draft agar mahasiswa bisa edit
        ]);

        return redirect()
            ->route('safety-officer.risk-assessment.index')
            ->with('success', 'Permintaan revisi berhasil dikirim ke mahasiswa.');
    }

    /**
     * Lihat jadwal wawancara
     */
    public function schedules()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        $upcomingSchedules = RiskAssessment::with(['user', 'daftarLab'])
            ->where('safety_officer_id', $user->id)
            ->whereNotNull('jadwal_wawancara')
            ->where('jadwal_wawancara', '>=', now())
            ->orderBy('jadwal_wawancara')
            ->paginate(10, ['*'], 'upcoming');

        $pastSchedules = RiskAssessment::with(['user', 'daftarLab'])
            ->where('safety_officer_id', $user->id)
            ->whereNotNull('jadwal_wawancara')
            ->where('jadwal_wawancara', '<', now())
            ->orderBy('jadwal_wawancara', 'desc')
            ->paginate(10, ['*'], 'past');

        return view('safety-officer.risk-assessment.schedules', compact('upcomingSchedules', 
        'pastSchedules', 'labs', 'user'));
    }

       // Pengumuman
        public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();
        
        return view('safety-officer.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }


    public function createPengumuman()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        return view('safety-officer.pengumuman.create', compact('user', 'labs'));
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
            return redirect()->route('safety-officer.pengumuman.index')
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
        
        return view('safety-officer.pengumuman.edit', compact('pengumuman', 'user', 'labs'));
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
            return redirect()->route('safety-officer.pengumuman.index')
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
    //         return redirect()->route('safety-officer.pengumuman.index')
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
            return redirect()->route('safety-officer.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }
}