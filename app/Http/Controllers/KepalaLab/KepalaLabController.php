<?php

namespace App\Http\Controllers\KepalaLab;

use App\Http\Controllers\Controller;
use App\Mail\RiskAssessmentMail;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\PeminjamanRuangan;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class KepalaLabController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $isKepalaLab = $user->isKepalaLaboratorium();
        $kepalaLab = $user;

        $managedLabs = collect();
        if ($isKepalaLab) {
            $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Labolatorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            if ($managedLabs->isEmpty() && Schema::hasColumn('daftar_labs', 'Kepala_Laboratorium')) {
                $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Laboratorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            }
        }

        $labIds = $managedLabs->pluck('id')->toArray();

        $riskAssessments = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
        ])
            ->where('status', 'menunggu_kepala_lab')
            ->when(!empty($labIds), function ($query) use ($labIds) {
            $query->whereIn('daftar_lab_id', $labIds);
        }, function ($query) {
            $query->whereRaw('1 = 0');
        })
            ->latest()
            ->paginate(10);

        $riwayat = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
        ])
            ->where('kepala_lab_id', $user->id)
            ->when(!empty($labIds), function ($query) use ($labIds) {
            $query->whereIn('daftar_lab_id', $labIds);
        }, function ($query) {
            $query->whereRaw('1 = 0');
        })
            ->whereNotIn('status', ['menunggu_kepala_lab'])
            ->latest()
            ->paginate(10);

        return view('kepala-lab.risk-assessment.index', compact('riskAssessments', 'riwayat', 'user', 'labs'));
    }

    public function show($id)
    {
        $labs = DaftarLab::all();
        $user = Auth::user();

        $isKepalaLab = $user->isKepalaLaboratorium();
        $kepalaLab = $user;

        $managedLabs = collect();
        if ($isKepalaLab) {
            $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Labolatorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            if ($managedLabs->isEmpty() && Schema::hasColumn('daftar_labs', 'Kepala_Laboratorium')) {
                $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Laboratorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            }
        }

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

        
        
        
        

        return view('kepala-lab.risk-assessment.review', compact('riskAssessment', 'labs', 'user'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $isKepalaLab = $user->isKepalaLaboratorium();
        $kepalaLab = $user;

        $managedLabs = collect();
        if ($isKepalaLab) {
            $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Labolatorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            if ($managedLabs->isEmpty() && Schema::hasColumn('daftar_labs', 'Kepala_Laboratorium')) {
                $managedLabs = DaftarLab::whereRaw('LOWER(Kepala_Laboratorium) = LOWER(?)', [$kepalaLab->Nama])->get();
            }
        }

        $riskAssessment = RiskAssessment::findOrFail($id);

        $labIds = $managedLabs->pluck('id')->toArray();
        if (empty($labIds) || !in_array($riskAssessment->daftar_lab_id, $labIds)) {
            abort(403, 'Anda tidak memiliki akses ke Risk Assessment ini.');
        }

        if ($riskAssessment->status !== 'menunggu_kepala_lab') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses sebelumnya.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'kepala_lab_id' => Auth::user()->id,
                'kepala_lab_nama' => Auth::user()->Nama,
                'nomor_identitas_kepala_lab' => Auth::user()->nomor_identitas,
                'persetujuan_kepala_lab' => $disetujui,
                'catatan_kepala_lab' => $request->catatan,
                'tanggal_persetujuan_kepala_lab' => now(),
                'status' => $disetujui ? 'disetujui' : 'ditolak',
            ]);

            if ($disetujui) {
                $riskAssessment->updateBatasWaktuPeminjaman();

                $riskAssessment->generateIdRa();

                \Log::info('Batas waktu peminjaman di-set untuk RA ID: ' . $riskAssessment->id, [
                    'mahasiswa' => $riskAssessment->nama,
                    'id_ra' => $riskAssessment->id_ra,
                    'tanggal_persetujuan' => $riskAssessment->tanggal_persetujuan_kepala_lab,
                    'batas_waktu_peminjaman' => $riskAssessment->batas_waktu_peminjaman,
                    'durasi' => $riskAssessment->durasi_batas_peminjaman . ' bulan',
                ]);
            }

            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => $disetujui ? 'Menyetujui Risk Assessment' : 'Menolak Risk Assessment',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul} oleh {$riskAssessment->nama}"
                . ($disetujui ? " (Batas waktu peminjaman: {$riskAssessment->getBatasWaktuPeminjamanFormatted()})" : ''),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            $message = $disetujui
                ? 'Risk Assessment berhasil disetujui! Mahasiswa dapat mengajukan peminjaman alat sampai '
                . $riskAssessment->getBatasWaktuPeminjamanFormatted() . '.'
                : 'Risk Assessment ditolak.';

            Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'hasil_kalab', $request->catatan));

            return redirect()
                ->route('kepala-lab.risk-assessment.index')
                ->with('success', $message);

        }
        catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error saat approve RA: ' . $e->getMessage(), [
                'risk_assessment_id' => $riskAssessment->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'kepala_lab_id' => Auth::user()->id,
                'kepala_lab_nama' => Auth::user()->Nama,
                'nomor_identitas_kepala_lab' => Auth::user()->nomor_identitas,
                'catatan_kepala_lab' => $request->catatan_revisi,
                'status' => 'draft',
            ]);

            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Meminta Revisi Risk Assessment',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()
                ->route('kepala-lab.risk-assessment.index')
                ->with('success', 'Permintaan revisi berhasil dikirim.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengirim permintaan revisi: ' . $e->getMessage());
        }
    }

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
            'total_tahun_ini' => RiskAssessment::whereYear('created_at', now()->year)->count(),
            'ruangan_menunggu' => PeminjamanRuangan::whereIn('status', ['disetujui_laboran', 'menunggu_kepala_lab'])->count(),
            'ruangan_disetujui_bulan_ini' => PeminjamanRuangan::where('status', 'disetujui')
            ->whereMonth('tanggal_persetujuan_kepala_lab', now()->month)
            ->count(),
        ];

        $recentApprovals = RiskAssessment::with(['user', 'daftarLab'])
            ->where('status', 'disetujui')
            ->latest('tanggal_persetujuan_kepala_lab')
            ->limit(5)
            ->get();

        $recentRoomBorrowings = PeminjamanRuangan::with(['daftarLab'])
            ->whereIn('status', ['disetujui', 'ditolak', 'dikembalikan', 'disetujui_final'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('kepala-lab.dashboard', compact('statistics', 'recentApprovals', 'recentRoomBorrowings', 'labs', 'user'));
    }

    public function report(Request $request)
    {
        $query = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
        ]);

        $user = Auth::user();
        $labs = DaftarLab::all();

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('lab_id') && $request->lab_id !== 'all') {
            $query->where('daftar_lab_id', $request->lab_id);
        }

        if ($request->has('kategori_resiko') && $request->kategori_resiko !== 'all') {
            $query->where('kategori_resiko_dosen', $request->kategori_resiko);
        }

        if ($request->has('jenis_ra') && $request->jenis_ra !== 'all') {
            $query->where('jenis_ra', $request->jenis_ra);
        }

        $riskAssessments = $query->latest()->paginate(20);

        return view('kepala-lab.risk-assessment.report', compact('riskAssessments', 'user', 'labs'));
    }


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

            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Membuat Pengumuman',
                'description' => "Judul: {$request->judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('kepala-lab.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dibuat!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat pengumuman: ' . $e->getMessage());
        }
    }

    public function editPengumuman($id)
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::findOrFail($id);
        $labs = DaftarLab::all();

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

            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengupdate Pengumuman',
                'description' => "Judul: {$request->judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('kepala-lab.pengumuman.index')
                ->with('success', 'Pengumuman berhasil diupdate!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengupdate pengumuman: ' . $e->getMessage());
        }
    }

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

            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Menghapus Pengumuman',
                'description' => "Judul: {$judul}",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('kepala-lab.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }

    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('kepala-lab.profil', compact('user', 'labs'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Phone' => 'required|string|max:20',
            'Email' => 'required|email|unique:daftar_users,Email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'Nama' => $request->Nama,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $data['foto'] = $filename;

                if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
                    unlink(public_path('uploads/profile/' . $user->foto));
                }
            }

            if ($request->hasFile('ttd')) {
                $file = $request->file('ttd');
                $filename = time() . '_ttd.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/ttd'), $filename);
                $data['ttd'] = $filename;

                if ($user->ttd && file_exists(public_path('uploads/ttd/' . $user->ttd))) {
                    unlink(public_path('uploads/ttd/' . $user->ttd));
                }
            }

            $user->update($data);

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

    public function peminjamanRuanganIndex()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        
        if (!$user->isKepalaLaboratorium()) {
            return redirect()->route('kepala-lab.dashboard')->with('error', 'Akses ditolak.');
        }

        
        $peminjamanMenunggu = PeminjamanRuangan::whereIn('status', ['disetujui_laboran', 'menunggu_kepala_lab'])
            ->with(['daftarLab', 'laboran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'waiting');

        
        $peminjamanDiproses = PeminjamanRuangan::whereIn('status', ['disetujui', 'ditolak', 'dikembalikan', 'disetujui_final'])
            ->with(['daftarLab', 'laboran', 'kepalaLab'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'processed');

        return view('kepala-lab.peminjaman-ruangan.index', compact(
            'user',
            'labs',
            'peminjamanMenunggu',
            'peminjamanDiproses'
        ));
    }

    /**
     * Detail peminjaman ruangan
     * Semua kepala lab dapat melihat detail peminjaman ruangan dari semua lab
     */
    public function peminjamanRuanganShow($id)
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $peminjaman = PeminjamanRuangan::with([
            'user',
            'daftarLab',
            'laboran',
            'kepalaLab',
        ])->findOrFail($id);

        
        if (!$user->isKepalaLaboratorium()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        return view('kepala-lab.peminjaman-ruangan.show', compact(
            'user',
            'labs',
            'peminjaman'
        ));
    }

    /**
     * Approve peminjaman ruangan
     * Semua kepala lab dapat approve peminjaman ruangan dari semua lab
     */
    public function peminjamanRuanganApprove(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        
        if (!$user->isKepalaLaboratorium()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk approve');
        }

        if (!in_array($peminjaman->status, ['disetujui_laboran', 'menunggu_kepala_lab'])) {
            return back()->with('error', 'Status peminjaman ruangan tidak valid untuk disetujui');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'kepala_lab_id' => $user->id,
                'persetujuan_kepala_lab' => true,
                'catatan_kepala_lab' => $request->catatan,
                'tanggal_persetujuan_kepala_lab' => now(),
                'status' => 'disetujui',
            ]);

            
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Approve Peminjaman Ruangan',
                'description' => "Peminjaman ruangan ID: {$id} - {$peminjaman->user_nama} - Tanggal: {$peminjaman->tanggal}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Peminjaman ruangan berhasil disetujui');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal approve peminjaman ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Reject peminjaman ruangan
     * Semua kepala lab dapat reject peminjaman ruangan dari semua lab
     */
    public function peminjamanRuanganReject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        
        if (!$user->isKepalaLaboratorium()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk reject');
        }

        if (!in_array($peminjaman->status, ['disetujui_laboran', 'menunggu_kepala_lab'])) {
            return back()->with('error', 'Status peminjaman ruangan tidak valid untuk ditolak');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'kepala_lab_id' => $user->id,
                'persetujuan_kepala_lab' => false,
                'catatan_kepala_lab' => $request->catatan,
                'tanggal_persetujuan_kepala_lab' => now(),
                'status' => 'ditolak',
            ]);

            
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Reject Peminjaman Ruangan',
                'description' => "Peminjaman ruangan ID: {$id} - {$peminjaman->user_nama} - Alasan: {$request->catatan}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return back()->with('success', 'Peminjaman ruangan berhasil ditolak');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal reject peminjaman ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Riwayat Peminjaman Ruangan
     */
    public function peminjamanRuanganReport(Request $request)
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $query = PeminjamanRuangan::with(['daftarLab', 'laboran', 'kepalaLab']);

        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        
        if ($request->filled('lab_id') && $request->lab_id !== 'all') {
            $query->where('daftar_lab_id', $request->lab_id);
        }

        $riwayat = $query->latest()->paginate(20);

        return view('kepala-lab.peminjaman-ruangan.report', compact('user', 'labs', 'riwayat'));
    }
}
