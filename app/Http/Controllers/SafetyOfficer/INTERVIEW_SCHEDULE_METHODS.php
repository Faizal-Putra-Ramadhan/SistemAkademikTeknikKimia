<?php
/**
 * NEW METHODS FOR INTERVIEW SCHEDULE OPTIONS
 * Add these methods to app\Http\Controllers\SafetyOfficer\SafetyOfficerController
 */

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
        'schedule_options.*.jadwal' => 'required|date|after:now',
        'schedule_options.*.tempat' => 'required|string|max:255',
        'catatan' => 'nullable|string|max:1000',
    ], [
        'schedule_options.required' => 'Minimal 2 opsi jadwal wawancara harus dibuat',
        'schedule_options.min' => 'Minimal 2 opsi jadwal wawancara',
        'schedule_options.max' => 'Maksimal 5 opsi jadwal wawancara',
        'schedule_options.*.jadwal.required' => 'Jadwal wawancara wajib diisi',
        'schedule_options.*.jadwal.after' => 'Jadwal harus setelah waktu sekarang',
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
        'description' => "RA ID: {$id} - {$riskAssessment->nama} - " . count($scheduleOptions) . " opsi jadwal",
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
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Validasi bahwa ada jadwal_wawancara_options
    if (!$riskAssessment->jadwal_wawancara_options || empty($riskAssessment->jadwal_wawancara_options)) {
        return response()->json(['error' => 'Belum ada opsi jadwal yang dibuat'], 400);
    }

    $scheduleIndex = $request->schedule_index;
    $options = $riskAssessment->jadwal_wawancara_options;

    if (!isset($options[$scheduleIndex])) {
        return response()->json(['error' => 'Opsi jadwal tidak valid'], 400);
    }

    $selectedSchedule = $options[$scheduleIndex];

    // Update jadwal yang dipilih
    $riskAssessment->update([
        'jadwal_wawancara' => $selectedSchedule['jadwal'],
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

    // Kirim email ke Safety Officer
    Mail::to($riskAssessment->safetyOfficer->Email)->send(new RiskAssessmentMail($riskAssessment, 'jadwal_dipilih_mahasiswa'));

    return response()->json([
        'success' => true,
        'message' => 'Jadwal wawancara berhasil dipilih. Safety Officer akan diberitahu.',
        'scheduled_date' => \Carbon\Carbon::parse($selectedSchedule['jadwal'])->format('d M Y, H:i'),
        'scheduled_place' => $selectedSchedule['tempat'],
    ]);
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
