<?php

// ============================================================================
// FILE: app/Http/Controllers/SafetyOfficer/SafetyOfficerController.php
// ============================================================================

namespace App\Http\Controllers\SafetyOfficer;

use App\Http\Controllers\Controller;
use App\Mail\RiskAssessmentMail;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SafetyOfficerController extends Controller
{
    /**
     * Dashboard Safety Officer
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Satu Safety Officer untuk semua laboratorium
        $labs = DaftarLab::orderBy('Nama_Laboratorium')->get();

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
            'pernyataanMahasiswa',
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
            'jadwal_wawancara' => 'required|date|after:yesterday',
            'tempat_wawancara' => 'nullable|string|max:255', // BARU: validasi untuk tempat wawancara
            'catatan' => 'nullable|string|max:1000',
        ], [
            'jadwal_wawancara.required' => 'Jadwal wawancara wajib diisi',
            'jadwal_wawancara.date' => 'Format jadwal tidak valid',
            'jadwal_wawancara.after' => 'Jadwal harus hari ini atau nanti',
            'tempat_wawancara.max' => 'Tempat wawancara maksimal 255 karakter', // BARU
            'catatan.max' => 'Catatan maksimal 1000 karakter',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        $riskAssessment->update([
            'safety_officer_id' => Auth::user()->id,
            'safety_officer_nama' => Auth::user()->Nama,
            'nomor_identitas_safety_officer' => Auth::user()->nomor_identitas,
            'jadwal_wawancara' => $request->jadwal_wawancara,
            'tempat_wawancara' => $request->tempat_wawancara, // BARU: simpan tempat wawancara
            'catatan_safety_officer' => $request->catatan,
        ]);

        // BARU: Log dengan info tempat jika ada
        $logDescription = "Jadwal wawancara untuk RA ID: {$id} - {$riskAssessment->nama}";
        if ($request->tempat_wawancara) {
            $logDescription .= " di {$request->tempat_wawancara}";
        }

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menjadwalkan Wawancara',
            'description' => $logDescription,
            'ip_address' => request()->ip(),
        ]);

        Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'jadwal_so'));

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

        // Gunakan Transaction untuk memastikan data aman
        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'safety_officer_id' => Auth::user()->id,
                'safety_officer_nama' => Auth::user()->Nama,
                'nomor_identitas_safety_officer' => Auth::user()->nomor_identitas,
                'persetujuan_safety_officer' => $disetujui,
                'status' => $disetujui ? 'menunggu_kepala_lab' : 'ditolak',
                'tanggal_persetujuan_safety_officer' => now(),
                'catatan_safety_officer' => $request->catatan,
            ]);

            if ($disetujui) {
                // KIRIM EMAIL KE MAHASISWA
                if ($riskAssessment->user && $riskAssessment->user->Email) {
                    \Mail::to($riskAssessment->user->Email)
                        ->send(new \App\Mail\RiskAssessmentMail($riskAssessment, 'approved_safety_officer'));
                }

                // AMBIL SEMUA USER DENGAN ROLE 'Kepala Lab'
                $kepalaLabs = \App\Models\DaftarUser::where('Role_User', 'Kepala Laboratorium')->get();

                foreach ($kepalaLabs as $kalab) {
                    if ($kalab->Email) {
                        \Mail::to($kalab->Email)->send(new \App\Mail\RiskAssessmentMail($riskAssessment, 'ke_kepala_lab'));
                    }
                }
            }
            else {
                // KIRIM EMAIL KE MAHASISWA JIKA DITOLAK
                if ($riskAssessment->user && $riskAssessment->user->Email) {
                    \Mail::to($riskAssessment->user->Email)
                        ->send(new \App\Mail\RiskAssessmentMail($riskAssessment, 'rejected_safety_officer'));
                }
            }

            DB::commit();

            return redirect()->route('safety-officer.risk-assessment.index')
                ->with('success', 'Berhasil. Email notifikasi telah dikirim ke mahasiswa dan Kepala Lab.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
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

        // Log aktivitas
        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Request Revisi Risk Assessment',
            'description' => "RA ID: {$id} - {$riskAssessment->nama}",
            'ip_address' => request()->ip(),
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

    /**
     * Update jadwal wawancara yang sudah ada
     * BARU: Method untuk mengupdate jadwal dan tempat wawancara
     */
    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'jadwal_wawancara' => 'required|date',
            'tempat_wawancara' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'jadwal_wawancara.required' => 'Jadwal wawancara wajib diisi',
            'jadwal_wawancara.date' => 'Format jadwal tidak valid',
            'tempat_wawancara.max' => 'Tempat wawancara maksimal 255 karakter',
            'catatan.max' => 'Catatan maksimal 1000 karakter',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        // Pastikan yang mengupdate adalah safety officer yang bersangkutan
        if ($riskAssessment->safety_officer_id !== Auth::user()->id) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengupdate jadwal ini.']);
        }

        $riskAssessment->update([
            'jadwal_wawancara' => $request->jadwal_wawancara,
            'tempat_wawancara' => $request->tempat_wawancara,
            'catatan_safety_officer' => $request->catatan,
        ]);

        // Log aktivitas
        $logDescription = "Update jadwal wawancara untuk RA ID: {$id} - {$riskAssessment->nama}";
        if ($request->tempat_wawancara) {
            $logDescription .= " di {$request->tempat_wawancara}";
        }

        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Update Jadwal Wawancara',
            'description' => $logDescription,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('safety-officer.risk-assessment.schedules')
            ->with('success', 'Jadwal wawancara berhasil diupdate.');
    }

    // ========================================================================
    // PENGUMUMAN SECTION
    // ========================================================================

    /**
     * Tampilkan daftar pengumuman
     */
    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();

        return view('safety-officer.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }

    /**
     * Tampilkan halaman profil
     */
    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('safety-officer.profil', compact('user', 'labs'));
    }

    /**
     * Update profil
     */
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

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Update Profil',
                'description' => 'Memperbarui informasi profil',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Profil berhasil diupdate!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
        }
    }

    // ========================================================================
    // NEW: INTERVIEW SCHEDULE OPTIONS METHODS
    // ========================================================================

    /**
     * Tampilkan form untuk membuat multiple jadwal wawancara options
     */
    public function showCreateScheduleOptions($id)
    {
        $riskAssessment = RiskAssessment::findOrFail($id);
        $user = Auth::user();

        // Pastikan status masih menunggu safety officer
        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        return view('safety-officer.risk-assessment.create-schedule-options', compact('riskAssessment', 'user'));
    }

    /**
     * Simpan multiple jadwal wawancara options dan kirim email ke mahasiswa
     */
    public function storeScheduleOptions(Request $request, $id)
    {
        $request->validate([
            'schedule_options' => 'required|array|min:2|max:5',
            'schedule_options.*.jadwal' => 'required|date|after:yesterday',
            'schedule_options.*.waktu' => 'required|date_format:H:i',
            'schedule_options.*.tempat' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'schedule_options.required' => 'Minimal 2 opsi jadwal wawancara harus dibuat',
            'schedule_options.min' => 'Minimal 2 opsi jadwal wawancara',
            'schedule_options.max' => 'Maksimal 5 opsi jadwal wawancara',
            'schedule_options.*.jadwal.required' => 'Jadwal wawancara wajib diisi',
            'schedule_options.*.jadwal.after' => 'Jadwal harus hari ini atau nanti',
            'schedule_options.*.waktu.required' => 'Jam wawancara wajib diisi',
            'schedule_options.*.waktu.date_format' => 'Format jam tidak valid (HH:MM)',
            'schedule_options.*.tempat.required' => 'Tempat wawancara wajib diisi',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_safety_officer') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses.']);
        }

        // Format schedule options
        $scheduleOptions = [];
        foreach ($request->schedule_options as $option) {
            $scheduleOptions[] = [
                'jadwal' => $option['jadwal'],
                'waktu' => $option['waktu'],
                'tempat' => $option['tempat'],
                'dipilih' => false,
            ];
        }

        // Update Risk Assessment
        $riskAssessment->update([
            'safety_officer_id' => Auth::user()->id,
            'safety_officer_nama' => Auth::user()->Nama,
            'nomor_identitas_safety_officer' => Auth::user()->nomor_identitas,
            'jadwal_wawancara_options' => $scheduleOptions,
            'catatan_safety_officer' => $request->catatan,
        ]);

        // Log aktivitas
        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Membuat Opsi Jadwal Wawancara',
            'description' => "RA ID: {$id} - {$riskAssessment->nama} - " . count($scheduleOptions) . ' opsi jadwal',
            'ip_address' => request()->ip(),
        ]);

        // Kirim email ke mahasiswa dengan opsi jadwal
        Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'jadwal_options_so'));

        return redirect()
            ->route('safety-officer.risk-assessment.show', $id)
            ->with('success', 'Opsi jadwal wawancara berhasil dibuat. Mahasiswa akan memilih jadwal yang sesuai.');
    }

    /**
     * Handle pemilihan jadwal oleh mahasiswa
     */
    public function selectScheduleOption(Request $request, $id)
    {
        $request->validate([
            'schedule_index' => 'required|integer|min:0',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        // Validasi bahwa user adalah mahasiswa yang membuat RA
        if ($riskAssessment->user_id !== Auth::user()->id) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        // Validasi bahwa ada jadwal_wawancara_options
        if (!$riskAssessment->jadwal_wawancara_options || empty($riskAssessment->jadwal_wawancara_options)) {
            return back()->withErrors(['schedule_index' => 'Belum ada opsi jadwal yang dibuat']);
        }

        $scheduleIndex = $request->schedule_index;
        $options = $riskAssessment->jadwal_wawancara_options;

        if (!isset($options[$scheduleIndex])) {
            return back()->withErrors(['schedule_index' => 'Opsi jadwal tidak valid']);
        }

        $selectedSchedule = $options[$scheduleIndex];

        // Gabungkan jadwal (tanggal) dengan waktu (jam) menjadi datetime lengkap
        $jadwalLengkap = \Carbon\Carbon::createFromFormat('Y-m-d H:i',
            \Carbon\Carbon::parse($selectedSchedule['jadwal'])->format('Y-m-d') . ' ' . $selectedSchedule['waktu']
        );

        // Update jadwal yang dipilih
        $riskAssessment->update([
            'jadwal_wawancara' => $jadwalLengkap,
            'tempat_wawancara' => $selectedSchedule['tempat'],
            'jadwal_wawancara_dipilih_at' => now(),
        ]);

        // Log aktivitas
        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Memilih Jadwal Wawancara',
            'description' => "RA ID: {$id} - {$riskAssessment->nama} memilih jadwal: " . $selectedSchedule['jadwal'],
            'ip_address' => request()->ip(),
        ]);

        // Send email to Safety Officer (async - jangan block)
        if ($riskAssessment->safetyOfficer && $riskAssessment->safetyOfficer->Email) {
            try {
                Mail::to($riskAssessment->safetyOfficer->Email)
                    ->send(new RiskAssessmentMail($riskAssessment, 'jadwal_dipilih_mahasiswa'));
            }
            catch (\Exception $e) {
                \Log::debug('Email send error (non-critical): ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('mahasiswa.risk-assessment.index')
            ->with('success', '✅ Jadwal wawancara berhasil dipilih! Safety Officer telah diberitahu melalui email.');
    }

    /**
     * List jadwal wawancara options yang pending untuk mahasiswa
     */
    public function listPendingScheduleOptions()
    {
        $user = Auth::user();

        // Ambil RA yang punya jadwal_wawancara_options namun belum dipilih
        $pendingSchedules = RiskAssessment::where('user_id', $user->id)
            ->whereNotNull('jadwal_wawancara_options')
            ->whereNull('jadwal_wawancara_dipilih_at')
            ->with(['safetyOfficer', 'daftarLab'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.risk-assessment.pending-schedules', compact('pendingSchedules', 'user'));
    }
}
