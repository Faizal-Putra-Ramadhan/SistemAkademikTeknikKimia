<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RaBahanKimia;
use App\Models\RaKategoriHazardBahan;
use App\Models\RaPeralatanOperasi;
use App\Models\RaPelakuKerja;
use App\Models\RaPernyataanMahasiswa;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RiskAssessmentController extends Controller
{
    /**
     * Tampilkan form create Risk Assessment
     */
    public function create($labId)
    {
        $lab = DaftarLab::findOrFail($labId);
        $user = Auth::user();
        $labs = DaftarLab::all();
        
        // Ambil daftar dosen untuk dropdown
        $dosens = DaftarUser::where('Role_User', 'Dosen')
                            ->orderBy('Nama')
                            ->get();

        return view('mahasiswa.risk-assessment.create', compact('lab', 'user', 'dosens', 'labs'));
    }

    

    /**
     * Simpan Risk Assessment baru
     */
    public function store(Request $request, $labId)
    {
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
            'peralatan.tekanan_tinggi' => 'required|boolean',
            'peralatan.suhu_tinggi' => 'required|boolean',
            'peralatan.nyala_api' => 'required|boolean',
            'peralatan.peralatan_berputar' => 'required|boolean',
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
            $riskAssessment = RiskAssessment::create([
                'user_id' => Auth::user()->id,
                'nama' => $request->nama,
                'nim' => $request->nim,
                'no_kontak' => $request->no_kontak,
                'alamat_kontak' => $request->alamat_kontak,
                'daftar_lab_id' => $labId,
                'jenis_ra' => $request->jenis_ra,
                'topik_judul' => $request->topik_judul,
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
                'dosen_pembimbing_nama' => $dosen->Nama,
                'status' => 'menunggu_dosen',
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

            return redirect()
                ->route('mahasiswa.risk-assessment.show', $riskAssessment->id)
                ->with('success', 'Risk Assessment berhasil diajukan! Menunggu persetujuan dosen pembimbing.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan detail Risk Assessment
     */
    public function show($id)
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
            'pernyataanMahasiswa'
        ])->findOrFail($id);
        $labs = DaftarLab::all();
        $user = Auth::user();

        return view('mahasiswa.risk-assessment.show', compact('riskAssessment' , 'labs' , 'user'));
    }

    /**
     * Tampilkan daftar Risk Assessment mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        $riskAssessments = RiskAssessment::with(['daftarLab', 'dosenPembimbing'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        $user = Auth::user();

        return view('mahasiswa.risk-assessment.index', compact('riskAssessments' , 'labs', 'user'));
    }

    /**
     * Edit Risk Assessment (hanya jika masih draft)
     */
    public function edit($id)
    {
        $riskAssessment = RiskAssessment::with([
            'bahanKimias',
            'kategoriHazardBahan',
            'peralatanOperasi',
            'pelakuKerja',
            'pernyataanMahasiswa'
        ])->findOrFail($id);

        $labs = DaftarLab::all();
        $user = Auth::user();
        // Hanya bisa edit jika masih draft
        if ($riskAssessment->status !== 'draft') {
            return redirect()
                ->route('mahasiswa.risk-assessment.show', $id)
                ->withErrors(['error' => 'Risk Assessment tidak dapat diedit karena sudah diajukan.']);
        }

        $lab = $riskAssessment->daftarLab;
        $dosens = DaftarUser::where('Role_User', 'Dosen')->orderBy('Nama')->get();

        return view('mahasiswa.risk-assessment.edit', compact('riskAssessment', 'lab', 'dosens' , 'labs', 'user'));
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
            'pernyataanMahasiswa'
        ])->findOrFail($id);

        // Implementasi PDF generation bisa menggunakan DomPDF atau library lain
        // return PDF::loadView('pdf.risk-assessment', compact('riskAssessment'))->download();
        
        return view('pdf.risk-assessment', compact('riskAssessment'));
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
            ->route('mahasiswa.risk-assessment.show', $id)
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
        'peralatan.tekanan_tinggi' => 'required|boolean',
        'peralatan.suhu_tinggi' => 'required|boolean',
        'peralatan.nyala_api' => 'required|boolean',
        'peralatan.peralatan_berputar' => 'required|boolean',
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
        ]);

        // 2. Update Bahan Kimia - Hapus yang lama, insert yang baru
        if ($request->has('bahan_kimia')) {
            // Hapus file MSDS lama
            $oldBahanKimias = RaBahanKimia::where('risk_assessment_id', $riskAssessment->id)->get();
            foreach ($oldBahanKimias as $oldBahan) {
                if ($oldBahan->msds_file) {
                    Storage::disk('public')->delete($oldBahan->msds_file);
                }
            }
            
            // Hapus data lama
            RaBahanKimia::where('risk_assessment_id', $riskAssessment->id)->delete();
            
            // Insert data baru
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

        // Check if user wants to submit for review
        if ($request->has('submit_for_review')) {
            $riskAssessment->update([
                'status' => 'menunggu_dosen',
            ]);
            
            return redirect()
                ->route('mahasiswa.risk-assessment.show', $id)
                ->with('success', 'Risk Assessment berhasil diupdate dan diajukan untuk review!');
        }

        return redirect()
            ->route('mahasiswa.risk-assessment.show', $id)
            ->with('success', 'Risk Assessment berhasil diupdate!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
}