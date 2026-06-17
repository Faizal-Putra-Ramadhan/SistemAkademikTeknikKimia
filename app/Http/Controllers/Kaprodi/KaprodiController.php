<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanRuanganMail;
use App\Mail\RiskAssessmentMail;
use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PeminjamanRuangan;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KaprodiController extends Controller
{
    /**
     * Dashboard Kaprodi
     */
    public function dashboard()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $statistics = [
            'menunggu' => RiskAssessment::where('status', 'menunggu_kaprodi')->count(),
            'disetujui_bulan_ini' => RiskAssessment::where('status', 'disetujui')
            ->whereMonth('tanggal_persetujuan_kaprodi', now()->month)
            ->whereYear('tanggal_persetujuan_kaprodi', now()->year)
            ->count(),
            'ditolak_bulan_ini' => RiskAssessment::where('status', 'ditolak')
            ->where('persetujuan_kaprodi', false)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count(),
            'total_tahun_ini' => RiskAssessment::whereYear('created_at', now()->year)->count(),
        ];

        $recentApprovals = RiskAssessment::with(['user', 'daftarLab'])
            ->where('kaprodi_id', $user->id)
            ->where('status', 'disetujui')
            ->latest('tanggal_persetujuan_kaprodi')
            ->limit(5)
            ->get();

        return view('kaprodi.dashboard', compact('statistics', 'recentApprovals', 'labs', 'user'));
    }

    /**
     * Daftar Risk Assessment yang menunggu persetujuan Kaprodi
     */
    public function index()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $riskAssessments = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'kepalaLab',
        ])
            ->where('status', 'menunggu_kaprodi')
            ->latest()
            ->paginate(10);

        $riwayat = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'kepalaLab',
        ])
            ->where('kaprodi_id', $user->id)
            ->whereNotIn('status', ['menunggu_kaprodi'])
            ->latest()
            ->paginate(10);

        return view('kaprodi.risk-assessment.index', compact('riskAssessments', 'riwayat', 'user', 'labs'));
    }

    /**
     * Detail Risk Assessment untuk final approval oleh Kaprodi
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
            'pernyataanMahasiswa',
        ])->findOrFail($id);

        return view('kaprodi.risk-assessment.review', compact('riskAssessment', 'labs', 'user'));
    }

    /**
     * Final Approval dari Kaprodi (dengan pengaturan durasi peminjaman)
     * FIXED: Type casting untuk durasi_batas_peminjaman
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'durasi_batas_peminjaman' => 'required_if:persetujuan,setuju|integer|min:1|max:12',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'durasi_batas_peminjaman.required_if' => 'Durasi batas peminjaman wajib diisi.',
            'durasi_batas_peminjaman.integer' => 'Durasi harus berupa angka.',
            'durasi_batas_peminjaman.min' => 'Durasi minimal 1 bulan.',
            'durasi_batas_peminjaman.max' => 'Durasi maksimal 12 bulan.',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        if ($riskAssessment->status !== 'menunggu_kaprodi') {
            return back()->withErrors(['error' => 'Risk Assessment sudah diproses sebelumnya.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        DB::beginTransaction();
        try {
            $updateData = [
                'kaprodi_id' => Auth::user()->id,
                'kaprodi_nama' => Auth::user()->Nama,
                'nomor_identitas_kaprodi' => Auth::user()->nomor_identitas,
                'persetujuan_kaprodi' => $disetujui,
                'catatan_kaprodi' => $request->catatan,
                'tanggal_persetujuan_kaprodi' => now(),
                'status' => $disetujui ? 'disetujui' : 'ditolak',
            ];

            // ✅ FIX: Cast ke integer dengan (int) atau intval()
            if ($disetujui) {
                $durasi = (int)$request->durasi_batas_peminjaman; // Cast ke integer
                $updateData['durasi_batas_peminjaman'] = $durasi;
            }

            $riskAssessment->update($updateData);

            // Otomatis set batas waktu peminjaman jika disetujui
            if ($disetujui) {
                $riskAssessment->updateBatasWaktuPeminjaman();

                \Log::info('Batas waktu peminjaman di-set oleh Kaprodi untuk RA ID: ' . $riskAssessment->id, [
                    'mahasiswa' => $riskAssessment->nama,
                    'tanggal_persetujuan' => $riskAssessment->tanggal_persetujuan_kaprodi,
                    'batas_waktu_peminjaman' => $riskAssessment->batas_waktu_peminjaman,
                    'durasi' => $riskAssessment->durasi_batas_peminjaman . ' bulan',
                ]);
            }

            // Log aktivitas
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => $disetujui ? 'Menyetujui Risk Assessment' : 'Menolak Risk Assessment',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul} oleh {$riskAssessment->nama}"
                . ($disetujui ? " (Durasi peminjaman: {$request->durasi_batas_peminjaman} bulan, sampai {$riskAssessment->getBatasWaktuPeminjamanFormatted()})" : ''),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            $message = $disetujui
                ? "Risk Assessment berhasil disetujui! Mahasiswa dapat mengajukan peminjaman alat sampai {$riskAssessment->getBatasWaktuPeminjamanFormatted()} ({$request->durasi_batas_peminjaman} bulan)."
                : 'Risk Assessment ditolak.';

            Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'hasil_kaprodi', $request->catatan));

            return redirect()
                ->route('kaprodi.risk-assessment.index')
                ->with('success', $message);

        }
        catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error saat Kaprodi approve RA: ' . $e->getMessage(), [
                'risk_assessment_id' => $riskAssessment->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Request revisi oleh Kaprodi
     */
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        DB::beginTransaction();
        try {
            $riskAssessment->update([
                'kaprodi_id' => Auth::user()->id,
                'catatan_kaprodi' => $request->catatan_revisi,
                'status' => 'draft',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Meminta Revisi Risk Assessment',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul}",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()
                ->route('kaprodi.risk-assessment.index')
                ->with('success', 'Permintaan revisi berhasil dikirim.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengirim permintaan revisi: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Risk Assessment
     */
    public function report(Request $request)
    {
        $query = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'kaprodi',
        ]);

        $user = Auth::user();
        $labs = DaftarLab::all();

        // Filter berdasarkan periode
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan lab
        if ($request->filled('lab_id') && $request->lab_id !== 'all') {
            $query->where('daftar_lab_id', $request->lab_id);
        }

        $riskAssessments = $query->latest()->paginate(20);

        return view('kaprodi.risk-assessment.report', compact('riskAssessments', 'user', 'labs'));
    }

    // =====================================================================
    // PENGUMUMAN
    // =====================================================================

    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();

        return view('kaprodi.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }

    public function createPengumuman()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('kaprodi.pengumuman.create', compact('user', 'labs'));
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

            return redirect()->route('kaprodi.pengumuman.index')
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

        return view('kaprodi.pengumuman.edit', compact('pengumuman', 'user', 'labs'));
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

            return redirect()->route('kaprodi.pengumuman.index')
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

            return redirect()->route('kaprodi.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }

    // Tambahkan di dalam class KaprodiController

    /**
     * Approve/Reject Perpanjangan Risk Assessment oleh Kaprodi
     */
    public function indexPerpanjangan()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        // Pengajuan perpanjangan yang menunggu
        $pengajuanPerpanjangan = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'kepalaLab',
        ])
            ->where('pengajuan_perpanjangan', true)
            ->where('persetujuan_perpanjangan_kaprodi', null)
            ->latest('tanggal_pengajuan_perpanjangan')
            ->paginate(10);

        // Riwayat perpanjangan yang sudah diproses
        $riwayatPerpanjangan = RiskAssessment::with([
            'user',
            'daftarLab',
            'dosenPembimbing',
            'safetyOfficer',
            'kepalaLab',
        ])
            ->where('pengajuan_perpanjangan', false)
            ->whereNotNull('persetujuan_perpanjangan_kaprodi')
            ->latest('tanggal_persetujuan_perpanjangan')
            ->paginate(10);

        return view('kaprodi.perpanjangan.index', compact('pengajuanPerpanjangan', 'riwayatPerpanjangan', 'user', 'labs'));
    }

    /**
     * Detail Pengajuan Perpanjangan untuk review
     */
    public function showPerpanjangan($id)
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
            'pernyataanMahasiswa',
        ])->findOrFail($id);

        // Pastikan memang ada pengajuan perpanjangan
        if (!$riskAssessment->pengajuan_perpanjangan) {
            return redirect()
                ->route('kaprodi.perpanjangan.index')
                ->with('error', 'Tidak ada pengajuan perpanjangan untuk Risk Assessment ini.');
        }

        return view('kaprodi.perpanjangan.review', compact('riskAssessment', 'labs', 'user'));
    }

    public function approvePerpanjangan(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($id);

        // Pastikan memang ada pengajuan perpanjangan
        if (!$riskAssessment->pengajuan_perpanjangan) {
            return back()->with('error', 'Tidak ada pengajuan perpanjangan untuk RA ini.');
        }

        $disetujui = $request->persetujuan === 'setuju';

        DB::beginTransaction();
        try {
            $updateData = [
                'persetujuan_perpanjangan_kaprodi' => $disetujui,
                'catatan_perpanjangan_kaprodi' => $request->catatan,
                'tanggal_persetujuan_perpanjangan' => now(),
                'pengajuan_perpanjangan' => false, // Reset status pengajuan
            ];

            if ($disetujui) {
                // Logika menambah masa berlaku: Tanggal saat ini + durasi yang diminta
                $durasi = (int)$riskAssessment->durasi_perpanjangan_diminta;
                $riskAssessment->batas_waktu_peminjaman = now()->addMonths($durasi);
                $riskAssessment->durasi_perpanjangan_disetujui = $durasi;
                $riskAssessment->jumlah_perpanjangan += 1;
                $riskAssessment->save();
            }

            $riskAssessment->update($updateData);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => $disetujui ? 'Menyetujui Perpanjangan RA' : 'Menolak Perpanjangan RA',
                'description' => "RA #{$riskAssessment->id} - {$riskAssessment->topik_judul} (Durasi: {$riskAssessment->durasi_perpanjangan_diminta} bulan)",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            // KIRIM EMAIL KE MAHASISWA
            Mail::to($riskAssessment->user->Email)->send(
                new RiskAssessmentMail($riskAssessment, 'hasil_perpanjangan', $request->catatan)
            );

            return redirect()->back()->with('success', 'Keputusan perpanjangan telah disimpan dan email notifikasi telah dikirim.');

        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses perpanjangan: ' . $e->getMessage());
        }
    }

    public function peminjamanRuanganIndex()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        // Kaprodi melihat semua peminjaman yang sedang aktif/berjalan (untuk monitoring)
        // Status: menunggu, disetujui_laboran, menunggu_kepala_lab, menunggu_kaprodi
        $peminjamanRuangans = PeminjamanRuangan::with(['daftarLab', 'laboran'])
            ->whereIn('status', ['menunggu', 'disetujui_laboran', 'menunggu_kepala_lab', 'menunggu_kaprodi'])
            ->latest()
            ->paginate(10);

        $riwayat = PeminjamanRuangan::with(['daftarLab', 'laboran'])
            ->whereIn('status', ['disetujui', 'ditolak', 'dikembalikan', 'disetujui_final'])
            ->latest()
            ->paginate(10);

        return view('kaprodi.peminjaman-ruangan.index', compact('peminjamanRuangans', 'riwayat', 'user', 'labs'));
    }

    /**
     * Detail Peminjaman Ruangan untuk review
     */
    public function peminjamanRuanganShow($id)
    {
        $labs = DaftarLab::all();
        $user = Auth::user();

        $peminjaman = PeminjamanRuangan::with(['daftarLab', 'laboran'])
            ->findOrFail($id);

        return view('kaprodi.peminjaman-ruangan.review', compact('peminjaman', 'labs', 'user'));
    }

    /**
     * Approve/Reject Peminjaman Ruangan oleh Kaprodi
     */
    public function peminjamanRuanganApprove(Request $request, $id)
    {
        $request->validate([
            'persetujuan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);

        if (!in_array($peminjaman->status, ['disetujui_laboran', 'menunggu_kaprodi'])) {
            return back()->withErrors(['error' => 'Peminjaman ruangan sudah diproses sebelumnya.']);
        }

        $disetujui = $request->persetujuan === 'setuju';

        DB::beginTransaction();
        try {
            $peminjaman->update([
                'kaprodi_id' => Auth::user()->id,
                'persetujuan_kaprodi' => $disetujui,
                'catatan_kaprodi' => $request->catatan,
                'tanggal_persetujuan_kaprodi' => now(),
                'status' => $disetujui ? 'disetujui' : 'ditolak',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => $disetujui ? 'Menyetujui Peminjaman Ruangan' : 'Menolak Peminjaman Ruangan',
                'description' => "Lab: {$peminjaman->daftarLab->Nama_Laboratorium}, Mahasiswa: {$peminjaman->user_nama}, Tanggal: {$peminjaman->tanggal} - {$peminjaman->tanggal_selesai}",
                'ip_address' => $request->ip(),
            ]);

            // ✅ KIRIM EMAIL KE MAHASISWA/DOSEN (hasil final)
            $peminjam = DaftarUser::where('Nama', $peminjaman->user_nama)->first();
            if ($peminjam && $peminjam->Email) {
                try {
                    Mail::to($peminjam->Email)->send(
                        new PeminjamanRuanganMail($peminjaman, 'hasil_kaprodi', $request->catatan)
                    );
                    \Log::info('Email hasil kaprodi berhasil dikirim ke: ' . $peminjam->Email);
                }
                catch (\Exception $e) {
                    \Log::error('Gagal mengirim email ke peminjam: ' . $e->getMessage());
                }
            }

            DB::commit();

            $message = $disetujui
                ? 'Peminjaman ruangan berhasil disetujui dan email notifikasi telah dikirim!'
                : 'Peminjaman ruangan ditolak dan email notifikasi telah dikirim.';

            return redirect()
                ->route('kaprodi.peminjaman-ruangan.index')
                ->with('success', $message);

        }
        catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error saat Kaprodi approve peminjaman ruangan: ' . $e->getMessage());

            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman profil
     */
    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('kaprodi.profil', compact('user', 'labs'));
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
}
