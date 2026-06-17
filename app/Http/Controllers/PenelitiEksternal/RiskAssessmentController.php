<?php

namespace App\Http\Controllers\PenelitiEksternal;

use App\Http\Controllers\Controller;
use App\Mail\RiskAssessmentMail;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\RaBahanKimia;
use App\Models\RaKategoriHazardBahan;
use App\Models\RaPelakuKerja;
use App\Models\RaPeralatanOperasi;
use App\Models\RaPernyataanMahasiswa;
use App\Models\RiskAssessment;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Risk Assessment Controller untuk Peneliti Eksternal
 * Extends functionality untuk Peneliti Eksternal role
 */
class RiskAssessmentController extends Controller
{
    /**
     * Get current user ID safely
     */
    protected function getCurrentUserId()
    {
        $user = Auth::user();
        if (! $user) {
            throw new \Exception('User tidak ter-authenticate');
        }

        // Gunakan id dari user, bukan UserID string
        return $user->id;
    }

    /**
     * Display list of Risk Assessments for Peneliti Eksternal
     */
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();
        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->with(['daftarLab', 'raBahanKimias'])
            ->latest()
            ->paginate(10);

        return view('peneliti-eksternal.risk-assessment.index', compact('riskAssessments', 'user', 'labs'));
    }

    /**
     * Show form for creating a new Risk Assessment
     */
    public function create($labId)
    {
        // Validasi user authenticated
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $lab = DaftarLab::findOrFail($labId);
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();
        $daftar_labs = DaftarLab::penelitian()->orderBy('Nama_Laboratorium')->get();

        if ($lab->lab_type !== 'penelitian') {
            return back()->with('error', 'Risk Assessment hanya dapat dibuat untuk lab penelitian.');
        }

        // Ambil daftar dosen untuk dropdown (termasuk user yang punya role Dosen + role lain)
        $dosens = DaftarUser::withDosenRole()
            ->orderBy('Nama')
            ->get();

        return view('peneliti-eksternal.risk-assessment.create', compact('lab', 'user', 'dosens', 'labs', 'daftar_labs'));
    }

    /**
     * Store a newly created Risk Assessment
     */
    public function store(Request $request, $labId)
    {
        // Verifikasi user authenticated
        if (! Auth::check() || ! Auth::user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $request->validate([
            // Data Mahasiswa
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'no_kontak' => 'required|string|max:20',
            'alamat_kontak' => 'required|string',
            'jenis_ra' => 'required|in:Penelitian,Praktikum,Lain-lain',
            'topik_judul' => 'required|string|max:255',
            'dosen_pembimbing_id' => 'required|exists:daftar_users,id',
            'laboratorium_id' => [
                'required',
                Rule::exists('daftar_labs', 'id')->where('lab_type', 'penelitian'),
            ],

            // Bahan Kimia
            'bahan_kimia.*.nama_bahan' => 'required|string|max:255',
            'bahan_kimia.*.sifat' => 'required|array',
            'bahan_kimia.*.lain_lain' => 'nullable|string',
            'bahan_kimia.*.msds_file' => 'nullable|file|mimes:pdf|max:5120',
            'kategori_hazard_bahan' => 'required|in:sangat_hazardous,hazardous,moderat,tidak_hazardous',

            // Peralatan & Kondisi Operasi
            'peralatan.tekanan_tinggi' => 'nullable|boolean',
            'peralatan.suhu_tinggi' => 'nullable|boolean',
            'peralatan.nyala_api' => 'nullable|boolean',
            'peralatan.peralatan_berputar' => 'nullable|boolean',
            'peralatan.temperatur_maksimum' => 'nullable|numeric',
            'peralatan.tekanan_maksimum' => 'nullable|numeric',
            'peralatan.kategori_hazard' => 'required|in:sangat_hazardous,hazardous,moderat,tidak_hazardous',

            // Pelaku Kerja
            'pelaku_kerja.menyadari_faktor_manusia' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_diri' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_orang_lain' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_lingkungan' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_peralatan' => 'required|boolean',
            'pelaku_kerja.paham_tindakan_kecelakaan' => 'required|boolean',
            'pelaku_kerja.penilaian_keterampilan' => 'required|in:ceroboh,kurang_terampil,cukup_terampil,sangat_terampil',

            // Pernyataan Mahasiswa
            'setuju_bertanggung_jawab' => 'required|accepted',
            'tanda_tangan' => 'nullable|string', // base64 signature
        ]);

        DB::beginTransaction();
        try {
            // Ambil data dosen
            $dosen = DaftarUser::findOrFail($request->dosen_pembimbing_id);

            // 1. Buat Risk Assessment utama
            $userId = Auth::user()->id;
            $riskAssessment = RiskAssessment::create([
                'user_id' => $userId,
                'nama' => $request->nama,
                'nim' => $request->nim,
                'no_kontak' => $request->no_kontak,
                'alamat_kontak' => $request->alamat_kontak,
                'daftar_lab_id' => $request->laboratorium_id,
                'jenis_ra' => $request->jenis_ra,
                'topik_judul' => $request->topik_judul,
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
                'dosen_pembimbing_nama' => $dosen->Nama,
                'nomor_identitas_dosen' => $dosen->nomor_identitas,
                'status' => 'menunggu_dosen',
            ]);

            // Verifikasi user_id tersimpan dengan benar
            if (! $riskAssessment->user_id) {
                throw new \Exception('Gagal menyimpan user_id. Silakan coba lagi.');
            }

            \Log::info('Risk Assessment created successfully', [
                'ra_id' => $riskAssessment->id,
                'user_id_stored' => $riskAssessment->user_id,
                'current_user_id' => Auth::user()->id,
            ]);

            // 2. Simpan Bahan Kimia
            if ($request->has('bahan_kimia')) {
                foreach ($request->bahan_kimia as $bahan) {
                    $msdsPath = null;
                    if (isset($bahan['msds_file']) && $bahan['msds_file']) {
                        $msdsPath = $bahan['msds_file']->store('msds', 'public');
                    }

                    $sifat = $bahan['sifat'] ?? [];
                    RaBahanKimia::create([
                        'risk_assessment_id' => $riskAssessment->id,
                        'nama_bahan' => $bahan['nama_bahan'],
                        'explosive' => in_array('explosive', $sifat),
                        'flammable' => in_array('flammable', $sifat),
                        'toxic' => in_array('toxic', $sifat),
                        'corrosive' => in_array('corrosive', $sifat),
                        'irritant' => in_array('irritant', $sifat),
                        'oxidizing' => in_array('oxidizing', $sifat),
                        'lain_lain' => $bahan['lain_lain'] ?? null,
                        'msds_file' => $msdsPath,
                    ]);
                }
            }

            // 3. Simpan Kategori Hazard Bahan
            RaKategoriHazardBahan::create([
                'risk_assessment_id' => $riskAssessment->id,
                'kategori' => $request->kategori_hazard_bahan,
            ]);

            // 4. Simpan Peralatan & Kondisi Operasi
            RaPeralatanOperasi::create([
                'risk_assessment_id' => $riskAssessment->id,
                'tekanan_tinggi' => $request->input('peralatan.tekanan_tinggi', false),
                'suhu_tinggi' => $request->input('peralatan.suhu_tinggi', false),
                'nyala_api' => $request->input('peralatan.nyala_api', false),
                'peralatan_berputar' => $request->input('peralatan.peralatan_berputar', false),
                'temperatur_maksimum' => $request->input('peralatan.temperatur_maksimum'),
                'tekanan_maksimum' => $request->input('peralatan.tekanan_maksimum'),
                'kategori_hazard' => $request->input('peralatan.kategori_hazard'),
            ]);

            // 5. Simpan Pelaku Kerja
            RaPelakuKerja::create([
                'risk_assessment_id' => $riskAssessment->id,
                'menyadari_faktor_manusia' => $request->input('pelaku_kerja.menyadari_faktor_manusia', false),
                'memahami_bahaya_diri' => $request->input('pelaku_kerja.memahami_bahaya_diri', false),
                'memahami_bahaya_orang_lain' => $request->input('pelaku_kerja.memahami_bahaya_orang_lain', false),
                'memahami_bahaya_lingkungan' => $request->input('pelaku_kerja.memahami_bahaya_lingkungan', false),
                'memahami_bahaya_peralatan' => $request->input('pelaku_kerja.memahami_bahaya_peralatan', false),
                'paham_tindakan_kecelakaan' => $request->input('pelaku_kerja.paham_tindakan_kecelakaan', false),
                'penilaian_keterampilan' => $request->input('pelaku_kerja.penilaian_keterampilan'),
            ]);

            // 6. Simpan Pernyataan Mahasiswa
            RaPernyataanMahasiswa::create([
                'risk_assessment_id' => $riskAssessment->id,
                'setuju_bertanggung_jawab' => $request->setuju_bertanggung_jawab,
                'tanda_tangan' => $request->tanda_tangan,
                'tanggal_pernyataan' => now(),
            ]);

            DB::commit();

            if ($dosen && $dosen->Email) {
                Mail::to($dosen->Email)->send(new RiskAssessmentMail($riskAssessment, 'ke_dosen'));
            }

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $riskAssessment->id)
                ->with('success', 'Risk Assessment berhasil diajukan! Menunggu persetujuan dosen pembimbing.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }
    }

    /**
     * Show a specific Risk Assessment
     */
    public function show($id)
    {
        $riskAssessment = RiskAssessment::with(['daftarLab', 'raBahanKimias', 'dosenPembimbing'])
            ->findOrFail($id);

        // Refresh dari database untuk memastikan data terbaru
        $riskAssessment->refresh();

        try {
            $currentUserId = $this->getCurrentUserId();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $raUserId = $riskAssessment->user_id;

        // Pastikan hanya pemilik yang bisa lihat
        if ((int) $raUserId !== (int) $currentUserId) {
            \Log::warning('Unauthorized access to Risk Assessment', [
                'ra_id' => $id,
                'ra_user_id' => $raUserId,
                'ra_user_id_type' => gettype($raUserId),
                'current_user_id' => $currentUserId,
                'current_user_id_type' => gettype($currentUserId),
            ]);
            abort(403, 'Anda tidak memiliki akses ke Risk Assessment ini');
        }

        $user = Auth::user();

        return view('peneliti-eksternal.risk-assessment.show', compact('riskAssessment', 'user'));
    }

    /**
     * Show form for editing Risk Assessment (only draft)
     */
    public function edit($id)
    {
        $riskAssessment = RiskAssessment::with([
            'bahanKimias',
            'kategoriHazardBahan',
            'peralatanOperasi',
            'pelakuKerja',
            'pernyataanMahasiswa',
        ])->findOrFail($id);

        $user = Auth::user();

        if ($riskAssessment->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Risk Assessment ini.');
        }

        // Hanya bisa edit jika masih draft
        if ($riskAssessment->status !== 'draft') {
            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->withErrors(['error' => 'Risk Assessment tidak dapat diedit karena sudah diajukan.']);
        }

        $dosens = DaftarUser::withDosenRole()
            ->orderBy('Nama')
            ->get();
        $labs = DaftarLab::penelitian()->get();

        return view('peneliti-eksternal.risk-assessment.edit', compact('riskAssessment', 'user', 'dosens', 'labs'));
    }

    /**
     * Update Risk Assessment
     */
    public function update(Request $request, $id)
    {
        $riskAssessment = RiskAssessment::findOrFail($id);

        // Check authorization - only owner can edit
        if ($riskAssessment->user_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Risk Assessment ini.');
        }

        // Check if still in draft status (only draft can be edited)
        if ($riskAssessment->status !== 'draft') {
            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('error', 'Hanya Risk Assessment dengan status Draft yang dapat diedit.');
        }

        // Validate request
        $request->validate([
            // Data Mahasiswa
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'no_kontak' => 'required|string|max:20',
            'alamat_kontak' => 'required|string',
            'jenis_ra' => 'required|in:Penelitian,Praktikum,Lain-lain',
            'topik_judul' => 'required|string|max:255',
            'dosen_pembimbing_id' => 'required|exists:daftar_users,id',

            // Bahan Kimia
            'bahan_kimia.*.nama_bahan' => 'required|string|max:255',
            'bahan_kimia.*.sifat' => 'required|array',
            'bahan_kimia.*.lain_lain' => 'nullable|string',
            'bahan_kimia.*.msds_file' => 'nullable|file|mimes:pdf|max:5120',
            'kategori_hazard_bahan' => 'required|in:sangat_hazardous,hazardous,moderat,tidak_hazardous',

            // Peralatan & Kondisi Operasi
            'peralatan.tekanan_tinggi' => 'boolean',
            'peralatan.suhu_tinggi' => 'boolean',
            'peralatan.nyala_api' => 'boolean',
            'peralatan.peralatan_berputar' => 'boolean',
            'peralatan.temperatur_maksimum' => 'nullable|numeric',
            'peralatan.tekanan_maksimum' => 'nullable|numeric',
            'peralatan.kategori_hazard' => 'in:sangat_hazardous,hazardous,moderat,tidak_hazardous',

            // Pelaku Kerja
            'pelaku_kerja.menyadari_faktor_manusia' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_diri' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_orang_lain' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_lingkungan' => 'required|boolean',
            'pelaku_kerja.memahami_bahaya_peralatan' => 'required|boolean',
            'pelaku_kerja.paham_tindakan_kecelakaan' => 'required|boolean',
            'pelaku_kerja.penilaian_keterampilan' => 'required|in:ceroboh,kurang_terampil,cukup_terampil,sangat_terampil',

            // Pernyataan Mahasiswa
            'setuju_bertanggung_jawab' => 'required|accepted',
            'tanda_tangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data dosen
            $dosen = DaftarUser::findOrFail($request->dosen_pembimbing_id);

            // 1. Update Risk Assessment utama
            $riskAssessment->update([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'no_kontak' => $request->no_kontak,
                'alamat_kontak' => $request->alamat_kontak,
                'jenis_ra' => $request->jenis_ra,
                'topik_judul' => $request->topik_judul,
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
                'dosen_pembimbing_nama' => $dosen->Nama,
                'nomor_identitas_dosen' => $dosen->nomor_identitas,
            ]);

            // 2. Update Bahan Kimia - Hapus yang lama, insert yang baru
            if ($request->has('bahan_kimia')) {
                $oldBahanKimias = RaBahanKimia::where('risk_assessment_id', $riskAssessment->id)->get();
                foreach ($oldBahanKimias as $oldBahan) {
                    if ($oldBahan->msds_file) {
                        Storage::disk('public')->delete($oldBahan->msds_file);
                    }
                }

                RaBahanKimia::where('risk_assessment_id', $riskAssessment->id)->delete();

                foreach ($request->bahan_kimia as $bahan) {
                    $msdsPath = null;
                    if (isset($bahan['msds_file']) && $bahan['msds_file']) {
                        $msdsPath = $bahan['msds_file']->store('msds', 'public');
                    }

                    $sifat = $bahan['sifat'] ?? [];
                    RaBahanKimia::create([
                        'risk_assessment_id' => $riskAssessment->id,
                        'nama_bahan' => $bahan['nama_bahan'],
                        'explosive' => in_array('explosive', $sifat),
                        'flammable' => in_array('flammable', $sifat),
                        'toxic' => in_array('toxic', $sifat),
                        'corrosive' => in_array('corrosive', $sifat),
                        'irritant' => in_array('irritant', $sifat),
                        'oxidizing' => in_array('oxidizing', $sifat),
                        'lain_lain' => $bahan['lain_lain'] ?? null,
                        'msds_file' => $msdsPath,
                    ]);
                }
            }

            // 3. Update Kategori Hazard Bahan
            RaKategoriHazardBahan::updateOrCreate(
                ['risk_assessment_id' => $riskAssessment->id],
                ['kategori' => $request->kategori_hazard_bahan]
            );

            // 4. Update Peralatan & Kondisi Operasi
            RaPeralatanOperasi::updateOrCreate(
                ['risk_assessment_id' => $riskAssessment->id],
                [
                    'tekanan_tinggi' => $request->input('peralatan.tekanan_tinggi', false),
                    'suhu_tinggi' => $request->input('peralatan.suhu_tinggi', false),
                    'nyala_api' => $request->input('peralatan.nyala_api', false),
                    'peralatan_berputar' => $request->input('peralatan.peralatan_berputar', false),
                    'temperatur_maksimum' => $request->input('peralatan.temperatur_maksimum'),
                    'tekanan_maksimum' => $request->input('peralatan.tekanan_maksimum'),
                    'kategori_hazard' => $request->input('peralatan.kategori_hazard'),
                ]
            );

            // 5. Update Pelaku Kerja
            RaPelakuKerja::updateOrCreate(
                ['risk_assessment_id' => $riskAssessment->id],
                [
                    'menyadari_faktor_manusia' => $request->input('pelaku_kerja.menyadari_faktor_manusia', false),
                    'memahami_bahaya_diri' => $request->input('pelaku_kerja.memahami_bahaya_diri', false),
                    'memahami_bahaya_orang_lain' => $request->input('pelaku_kerja.memahami_bahaya_orang_lain', false),
                    'memahami_bahaya_lingkungan' => $request->input('pelaku_kerja.memahami_bahaya_lingkungan', false),
                    'memahami_bahaya_peralatan' => $request->input('pelaku_kerja.memahami_bahaya_peralatan', false),
                    'paham_tindakan_kecelakaan' => $request->input('pelaku_kerja.paham_tindakan_kecelakaan', false),
                    'penilaian_keterampilan' => $request->input('pelaku_kerja.penilaian_keterampilan'),
                ]
            );

            // 6. Update Pernyataan Mahasiswa
            RaPernyataanMahasiswa::updateOrCreate(
                ['risk_assessment_id' => $riskAssessment->id],
                [
                    'setuju_bertanggung_jawab' => $request->setuju_bertanggung_jawab,
                    'tanda_tangan' => $request->tanda_tangan,
                    'tanggal_pernyataan' => now(),
                ]
            );

            DB::commit();
            $riskAssessment->update([
                'status' => 'menunggu_dosen',
            ]);

            if ($request->has('submit_for_review')) {
                $riskAssessment->update([
                    'status' => 'menunggu_dosen',
                ]);

                return redirect()
                    ->route('peneliti-eksternal.risk-assessment.show', $id)
                    ->with('success', 'Risk Assessment berhasil diupdate dan diajukan untuk review!');
            }

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('success', 'Risk Assessment berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }
    }

    /**
     * Submit Risk Assessment to Kaprodi
     */
    public function ajukanKeKaprodi($id)
    {
        $riskAssessment = RiskAssessment::findOrFail($id);

        // Check authorization - hanya pemilik yang bisa mengajukan
        if ($riskAssessment->user_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengajukan Risk Assessment ini.');
        }

        // Check if bisa diajukan ke Kaprodi
        if (! $riskAssessment->bisaAjukanKeKaprodi()) {
            return back()->with('error', 'Risk Assessment ini tidak dapat diajukan ke Kaprodi. Pastikan sudah disetujui Kepala Lab.');
        }

        DB::beginTransaction();
        try {
            // Update status menjadi menunggu_kaprodi
            $riskAssessment->update([
                'status' => 'menunggu_kaprodi',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Mengajukan RA ke Kaprodi',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul}",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            $kaprodi = DaftarUser::where('Role_User', 'Kaprodi')->first();
            if ($kaprodi) {
                Mail::to($kaprodi->Email)->send(new RiskAssessmentMail($riskAssessment, 'ke_kaprodi'));
            }

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('success', 'Risk Assessment berhasil diajukan ke Kaprodi! Menunggu persetujuan akhir.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat mengajukan RA ke Kaprodi: '.$e->getMessage(), [
                'risk_assessment_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal mengajukan ke Kaprodi: '.$e->getMessage());
        }
    }

    /**
     * Download PDF Risk Assessment
     */
    public function downloadPdf($id)
    {
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
            'pernyataanMahasiswa',
        ])->findOrFail($id);

        if ($riskAssessment->user_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh Risk Assessment ini.');
        }

        // Implementasi PDF generation bisa menggunakan DomPDF atau library lain
        // return PDF::loadView('pdf.risk-assessment', compact('riskAssessment'))->download();

        // Path template
        $templatePath = storage_path('app/templates/template1.docx');

        App::setLocale('id');
        Carbon::setLocale('id');
        CarbonImmutable::setLocale('id');
        // Load template
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $current_time = Carbon::now('Asia/Jakarta')->translatedFormat(('d F Y'));
        $formatDate = fn ($value) => $value ? Carbon::parse($value)->translatedFormat('d F Y') : '-';
        // Isi nilai
        $phpWord->setValues([
            'NAMA_MAHASISWA' => $riskAssessment->nama ?? '-',
            'NIM' => $riskAssessment->nim ?? '-',
            'ALAMAT_MAHASISWA' => $riskAssessment->alamat_kontak ?? '-',
            'TELP_MAHASISWA' => $riskAssessment->no_kontak ?? '-',
            'NAMA_DOSEN' => $riskAssessment->dosen_pembimbing_nama ?? '-',
            'JUDUL_RA' => $riskAssessment->topik_judul ?? '-',
            'NAMA_KEPALA_LAB' => $riskAssessment->kepala_lab_nama ?? '-',
            'CURRENT_TIME' => $current_time ?? '-',
            'WAKTU_PENGAJUAN' => $formatDate($riskAssessment->created_at),
            'PENELITIAN' => $riskAssessment->jenis_ra == 'Penelitian' ? '✔' : '',
            'PRAKTIKUM' => $riskAssessment->jenis_ra == 'Praktikum' ? '✔' : '',
            'LAIN' => $riskAssessment->jenis_ra == 'Lain-lain' ? '✔' : '',
            'KATEGORI_BAHAN_KIMIA' => ucwords(str_replace('_', ' ', $riskAssessment->kategoriHazardBahan?->kategori ?? '-')),
            'TEMPERATURE_MAKS' => $riskAssessment->peralatanOperasi?->temperatur_maksimum ?? '-',
            'TEKANAN_MAKS' => $riskAssessment->peralatanOperasi?->tekanan_maksimum ?? '-',
            'KATEGORI_PERALATAN' => ucwords(str_replace('_', ' ', $riskAssessment->peralatanOperasi?->kategori_hazard ?? '-')),
            'PENILAIAN_KETERAMPILAN' => ucwords(str_replace('_', ' ', $riskAssessment->pelakuKerja?->penilaian_keterampilan ?? '-')),
            'KATEGORI_RISIKO_DOSEN' => ucwords(str_replace('_', ' ', $riskAssessment->kategori_resiko_dosen ?? '-')),
            'NAMA_SO' => $riskAssessment->safety_officer_nama ?? '-',
            'TGL_PERSETUJUAN_DOSEN' => $formatDate($riskAssessment->tanggal_persetujuan_dosen),
            'TGL_PERSETUJUAN_KLAB' => $formatDate($riskAssessment->tanggal_persetujuan_kepala_lab),
            'TGL_PERSETUJUAN_SO' => $formatDate($riskAssessment->tanggal_persetujuan_safety_officer),
            'NOMOR_IDENTITAS' => $riskAssessment->user?->nomor_identitas ?? '-',
            'NOMOR_IDENTITAS_DOSEN' => $riskAssessment->nomor_identitas_dosen ?? $riskAssessment->dosenPembimbing?->nomor_identitas ?? '-',
            'NOMOR_IDENTITAS_KEPALA_LAB' => $riskAssessment->nomor_identitas_kepala_lab ?? $riskAssessment->kepalaLab?->nomor_identitas ?? '-',
            'NAMA_LAB' => $riskAssessment->daftarLab?->Nama_Laboratorium ?? '-',
            'NAMA_KAPRODI' => $riskAssessment->kaprodi_nama ?? $riskAssessment->kaprodi?->Nama ?? '-',
            'PERSETUJUAN_MAHASISWA' => 'Disetujui',

        ]);

        $bahanList = $riskAssessment->bahanKimias;

        // selalu buat 10 baris
        $totalRows = 10;

        // cek apakah disetujui kaprodi
        if ($riskAssessment->persetujuan_kaprodi == 1) {
            $phpWord->setValue('PERSETUJUAN_KAPRODI', 'Disetujui');
        } else {
            $phpWord->setValue('PERSETUJUAN_KAPRODI', 'Tidak Disetujui');
        }

        // cek apakah disetujui kepala lab
        if ($riskAssessment->persetujuan_kepala_lab == 1) {
            $phpWord->setValue('PERSETUJUAN_KEPALA_LAB', 'Disetujui');
        } else {
            $phpWord->setValue('PERSETUJUAN_KEPALA_LAB', 'Tidak Disetujui');
        }

        // cek apakah disetujui safety officer
        if ($riskAssessment->persetujuan_safety_officer == 1) {
            $phpWord->setValue('PERSETUJUAN_SAFETY_OFFICER', 'Disetujui');
        } else {
            $phpWord->setValue('PERSETUJUAN_SAFETY_OFFICER', 'Tidak Disetujui');
        }

        // cek apakah disetujui dosen pembimbing
        if ($riskAssessment->persetujuan_dosen == 1) {
            $phpWord->setValue('PERSETUJUAN_DOSEN', 'Disetujui');
        } else {
            $phpWord->setValue('PERSETUJUAN_DOSEN', 'Tidak Disetujui');
        }

        for ($i = 1; $i <= $totalRows; $i++) {

            $bahan = $bahanList[$i - 1] ?? null;

            // helper fungsi tanda ceklis
            $cek = fn ($v) => $v == 1 ? '✓' : '';

            $phpWord->setValue("BAHAN_KIMIA_{$i}", $bahan->nama_bahan ?? '');

            $phpWord->setValue("EXPLOSIVE_{$i}", $bahan ? $cek($bahan->explosive) : '');
            $phpWord->setValue("FLAMMABLE_{$i}", $bahan ? $cek($bahan->flammable) : '');
            $phpWord->setValue("TOXIC_{$i}", $bahan ? $cek($bahan->toxic) : '');
            $phpWord->setValue("CORROSIVE_{$i}", $bahan ? $cek($bahan->corrosive) : '');
            $phpWord->setValue("IRRITANT_{$i}", $bahan ? $cek($bahan->irritant) : '');
            $phpWord->setValue("OXIDIXING_{$i}", $bahan ? $cek($bahan->oxidizing) : '');
            $phpWord->setValue("BAHAN_LAIN_{$i}", $bahan ? $cek($bahan->lain_lain) : '');
        }

        $ops = $riskAssessment->pernyataanMahasiswa;

        // fungsi helper
        $ya = function ($val) {
            return $val == 1 ? '✓' : '';
        };

        $tidak = function ($val) {
            return $val == 0 ? '✓' : '';
        };

        if ($riskAssessment->peralatanOperasi?->tekanan_tinggi == 1) {
            $phpWord->setValue('TTY', '✓');
            $phpWord->setValue('TTT', '');
        } else {
            $phpWord->setValue('TTY', '');
            $phpWord->setValue('TTT', '✓');
        }

        if ($riskAssessment->peralatanOperasi?->suhu_tinggi == 1) {
            $phpWord->setValue('SHY', '✓');
            $phpWord->setValue('SHT', '');
        } else {
            $phpWord->setValue('SHY', '');
            $phpWord->setValue('SHT', '✓');
        }

        if ($riskAssessment->peralatanOperasi?->nyala_api == 1) {
            $phpWord->setValue('NAY', '✓');
            $phpWord->setValue('NAT', '');
        } else {
            $phpWord->setValue('NAY', '');
            $phpWord->setValue('NAT', '✓');
        }

        if ($riskAssessment->peralatanOperasi?->peralatan_berputar == 1) {
            $phpWord->setValue('PBY', '✓');
            $phpWord->setValue('PBT', '');
        } else {
            $phpWord->setValue('PBY', '');
            $phpWord->setValue('PBT', '✓');
        }

        // mapping ke template
        // $phpWord->setValues([

        //     // Tekanan tinggi
        //     'TTY' => $ya($ops->tekanan_tinggi),
        //     'TTT' => $tidak($ops->tekanan_tinggi),

        //     // Suhu tinggi
        //     'SHY' => $ya($ops->suhu_tinggi),
        //     'SHT' => $tidak($ops->suhu_tinggi),

        //     // Nyala api
        //     'NAY' => $ya($ops->nyala_api),
        //     'NAT' => $tidak($ops->nyala_api),

        //     // Peralatan berputar
        //     'PBY' => $ya($ops->peralatan_berputar),
        //     'PBT' => $tidak($ops->peralatan_berputar),

        // ]);

        $ops = $riskAssessment->pelakuKerja;

        // fungsi helper
        $ya = function ($val) {
            return $val == 1 ? '✓' : '';
        };

        $tidak = function ($val) {
            return $val == 0 ? '✓' : '';
        };

        // mapping ke template
        $phpWord->setValues([

            // Tekanan tinggi
            'MFMY' => $ya($ops->menyadari_faktor_manusia),
            'MFMT' => $tidak($ops->menyadari_faktor_manusia),

            // Suhu tinggi
            'MBDY' => $ya($ops->memahami_bahaya_diri),
            'MBDT' => $tidak($ops->memahami_bahaya_diri),

            // Nyala api
            'MBRY' => $ya($ops->memahami_bahaya_orang_lain),
            'MBRT' => $tidak($ops->memahami_bahaya_orang_lain),

            // Peralatan berputar
            'MBLY' => $ya($ops->memahami_bahaya_lingkungan),
            'MBLT' => $tidak($ops->memahami_bahaya_lingkungan),

            'MBPY' => $ya($ops->memahami_bahaya_peralatan),
            'MBPT' => $tidak($ops->memahami_bahaya_peralatan),

            'PTKY' => $ya($ops->paham_tindakan_kecelakaan),
            'PTKT' => $tidak($ops->paham_tindakan_kecelakaan),

        ]);

        // Nama file final
        $fileName = 'Risk_Assessment_'.$riskAssessment->id.'.docx';

        // Simpan sementara di storage
        $savePath = storage_path('app/public/'.$fileName);

        $phpWord->saveAs($savePath);

        // Download lalu hapus setelah terkirim
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    /**
     * Form pengajuan perpanjangan
     */
    public function formPerpanjangan($id)
    {
        $riskAssessment = RiskAssessment::findOrFail($id);
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        if ($riskAssessment->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengajukan perpanjangan RA ini.');
        }

        if (! $riskAssessment->bisaAjukanPerpanjangan()) {
            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('error', 'Anda tidak dapat mengajukan perpanjangan untuk Risk Assessment ini.');
        }

        return view('peneliti-eksternal.risk-assessment.perpanjangan', compact('riskAssessment', 'user', 'labs'));
    }

    /**
     * Simpan pengajuan perpanjangan
     */
    public function ajukanPerpanjangan(Request $request, $id)
    {
        $request->validate([
            'alasan_perpanjangan' => 'required|string|min:50|max:1000',
            'durasi_perpanjangan_diminta' => 'required|integer|min:1|max:12',
        ], [
            'alasan_perpanjangan.required' => 'Alasan perpanjangan wajib diisi.',
            'alasan_perpanjangan.min' => 'Alasan perpanjangan minimal 50 karakter.',
            'durasi_perpanjangan_diminta.required' => 'Durasi perpanjangan wajib diisi.',
            'durasi_perpanjangan_diminta.min' => 'Durasi minimal 1 bulan.',
            'durasi_perpanjangan_diminta.max' => 'Durasi maksimal 12 bulan.',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->user_id !== Auth::user()->id) {
            abort(403);
        }

        if (! $riskAssessment->bisaAjukanPerpanjangan()) {
            return back()->with('error', 'Anda tidak dapat mengajukan perpanjangan untuk Risk Assessment ini.');
        }

        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'pengajuan_perpanjangan' => true,
                'alasan_perpanjangan' => $request->alasan_perpanjangan,
                'tanggal_pengajuan_perpanjangan' => now(),
                'durasi_perpanjangan_diminta' => (int) $request->durasi_perpanjangan_diminta,
            ]);

            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Mengajukan Perpanjangan RA',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul} (Durasi diminta: {$request->durasi_perpanjangan_diminta} bulan)",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            $kaprodi = DaftarUser::where('Role_User', 'Kaprodi')->first();
            if ($kaprodi && $kaprodi->Email) {
                Mail::to($kaprodi->Email)->send(new RiskAssessmentMail($riskAssessment, 'ajukan_perpanjangan'));
            }

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('success', 'Pengajuan perpanjangan berhasil dikirim! Menunggu persetujuan Kaprodi.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error saat mengajukan perpanjangan RA: '.$e->getMessage(), [
                'risk_assessment_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal mengajukan perpanjangan: '.$e->getMessage());
        }
    }

    /**
     * Batalkan pengajuan perpanjangan
     */
    public function batalkanPerpanjangan($id)
    {
        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->user_id !== Auth::user()->id) {
            abort(403);
        }

        if (! $riskAssessment->hasPendingPerpanjangan()) {
            return back()->with('error', 'Tidak ada pengajuan perpanjangan yang dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $kaprodi = DaftarUser::where('Role_User', 'Kaprodi')->first();
            $riskAssessment->update([
                'pengajuan_perpanjangan' => false,
                'alasan_perpanjangan' => null,
                'tanggal_pengajuan_perpanjangan' => null,
                'durasi_perpanjangan_diminta' => null,
            ]);

            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Membatalkan Pengajuan Perpanjangan RA',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul}",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            if ($kaprodi && $kaprodi->Email) {
                Mail::to($kaprodi->Email)->send(new RiskAssessmentMail($riskAssessment, 'batal_perpanjangan'));
            }

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.show', $id)
                ->with('success', 'Pengajuan perpanjangan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membatalkan pengajuan: '.$e->getMessage());
        }
    }

    /**
     * Handle interview schedule selection by Peneliti Eksternal
     */
    public function selectScheduleOption(Request $request, $id)
    {
        $request->validate([
            'schedule_index' => 'required|integer|min:0',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        // Validation for researcher ownership
        if ($riskAssessment->user_id !== Auth::user()->id) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses ke Risk Assessment ini.']);
        }

        // Validate schedule options existence
        if (!$riskAssessment->jadwal_wawancara_options || empty($riskAssessment->jadwal_wawancara_options)) {
            return back()->withErrors(['schedule_index' => 'Belum ada opsi jadwal yang disediakan oleh Safety Officer.']);
        }

        $scheduleIndex = $request->schedule_index;
        $options = $riskAssessment->jadwal_wawancara_options;

        if (!isset($options[$scheduleIndex])) {
            return back()->withErrors(['schedule_index' => 'Opsi jadwal tidak valid.']);
        }

        $selectedSchedule = $options[$scheduleIndex];

        // Format combined datetime
        try {
            $jadwalLengkap = Carbon::createFromFormat('Y-m-d H:i',
                Carbon::parse($selectedSchedule['jadwal'])->format('Y-m-d') . ' ' . $selectedSchedule['waktu']
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Format jadwal tidak valid: ' . $e->getMessage());
        }

        // Update Risk Assessment
        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'jadwal_wawancara' => $jadwalLengkap,
                'tempat_wawancara' => $selectedSchedule['tempat'],
                'jadwal_wawancara_dipilih_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Memilih Jadwal Wawancara',
                'description' => "Peneliti Eksternal memilih jadwal untuk RA ID: {$id} - {$riskAssessment->topik_judul}",
                'ip_address' => request()->ip(),
            ]);

            // Notify Safety Officer via email
            if ($riskAssessment->safetyOfficer && $riskAssessment->safetyOfficer->Email) {
                Mail::to($riskAssessment->safetyOfficer->Email)
                    ->send(new RiskAssessmentMail($riskAssessment, 'jadwal_dipilih_mahasiswa'));
            }

            DB::commit();

            return redirect()
                ->route('peneliti-eksternal.risk-assessment.index')
                ->with('success', '✅ Jadwal wawancara berhasil dipilih! Safety Officer telah diberitahu.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pemilihan jadwal: ' . $e->getMessage());
        }
    }
}
