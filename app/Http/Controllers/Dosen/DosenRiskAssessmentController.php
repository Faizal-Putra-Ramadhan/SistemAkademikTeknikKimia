<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Mail\RiskAssessmentMail;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DosenRiskAssessmentController extends Controller
{
    /**
     * Tampilkan daftar Risk Assessment yang menunggu persetujuan dosen
     */
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        $riskAssessments = RiskAssessment::with(['user', 'daftarLab'])
            ->where('dosen_pembimbing_id', $user->id)
            ->whereIn('status', ['menunggu_dosen'])
            ->latest()
            ->paginate(10);

        $riwayat = RiskAssessment::with(['user', 'daftarLab'])
            ->where('dosen_pembimbing_id', $user->id)
            ->whereNotIn('status', ['menunggu_dosen'])
            ->latest()
            ->paginate(10);

        return view('dosen.risk-assessment.index', compact('riskAssessments', 'riwayat', 'labs', 'user'));
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
            'bahanKimias',
            'kategoriHazardBahan',
            'peralatanOperasi',
            'pelakuKerja',
            'pernyataanMahasiswa',
        ])->findOrFail($id);

        // Pastikan ini memang risk assessment untuk dosen yang login
        if ($riskAssessment->dosen_pembimbing_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses ke Risk Assessment ini.');
        }
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('dosen.risk-assessment.review', compact('riskAssessment', 'labs', 'user'));
    }

    /**
     * Proses persetujuan dosen
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'kategori_resiko' => 'required|in:tinggi,sedang,rendah',
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        // Validasi akses
        if ($riskAssessment->dosen_pembimbing_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui Risk Assessment ini.');
        }

        if ($riskAssessment->status !== 'menunggu_dosen') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses sebelumnya.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        $riskAssessment->update([
            'kategori_resiko_dosen' => $request->kategori_resiko,
            'persetujuan_dosen' => $disetujui,
            'catatan_dosen' => $request->catatan,
            'tanggal_persetujuan_dosen' => now(),
            'status' => $disetujui ? 'menunggu_safety_officer' : 'ditolak',
            'nomor_identitas_dosen' => Auth::user()->nomor_identitas,
        ]);

        $message = $disetujui
            ? 'Risk Assessment berhasil disetujui. Akan dilanjutkan ke Safety Officer.'
            : 'Risk Assessment ditolak. Mahasiswa dapat mengajukan kembali setelah perbaikan.';

        // Di dalam method approve()
        // 1. Notif ke Mahasiswa
        Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'dosen_setuju'));

        // 2. Notif ke Semua Safety Officer
        $safetyOfficers = DaftarUser::where('Role_User', 'Safety Officer')->get();
        foreach ($safetyOfficers as $so) {
            if ($so->Email) {
                Mail::to($so->Email)->send(new RiskAssessmentMail($riskAssessment, 'ke_so'));
            }
        }

        return redirect()
            ->route('dosen.risk-assessment.index')
            ->with('success', $message);
    }

    /**
     * Minta revisi dari mahasiswa
     */
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->dosen_pembimbing_id !== Auth::user()->id) {
            abort(403);
        }

        $riskAssessment->update([
            'catatan_dosen' => $request->catatan_revisi,
            'status' => 'draft', // Kembalikan ke draft untuk revisi
        ]);

        return redirect()
            ->route('dosen.risk-assessment.index')
            ->with('success', 'Permintaan revisi berhasil dikirim ke mahasiswa.');
    }
}
