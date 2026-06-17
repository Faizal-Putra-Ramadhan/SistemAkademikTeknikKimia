<?php

namespace App\Http\Controllers;

use App\Mail\BebasLabApprovedKepalaLabMail;
use App\Mail\BebasLabApprovedMail;
use App\Mail\PeminjamanAlatMail;
use App\Mail\PeminjamanRuanganMail;
use App\Mail\RiskAssessmentMail;
use App\Models\ActivityLog;
use App\Models\AlatLab;
use App\Models\BebasLabApproval;
use App\Models\BebasLabRequest; // Pastikan Model AlatLab sudah ada
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanRuangan;
use App\Models\PengajuanPenelitian;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LaboranController extends Controller
{
    public function dashboard($id = null)
    {
        $user = Auth::user();

        // Ambil data laboran berdasarkan user yang login
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        if (! $laboran) {
            return redirect()->route('login')->with('error', 'Data laboran tidak ditemukan');
        }

        // Ambil lab aktif: PRIORITAS: parameter route > session > lab pertama
        if ($id) {
            // Jika ada parameter route, gunakan itu
            $daftarLab = DaftarLab::findOrFail($id);
            // Validasi bahwa lab ini dikelola oleh laboran
            if (! $laboran->laboratoriums->contains('id', $id)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laboratorium ini.');
            }
            // Simpan ke session untuk penggunaan selanjutnya
            session(['active_lab_id' => $id]);
            request()->session()->put('active_lab_id', $id);
        } else {
            // Jika tidak ada parameter, cek session
            $activeLabId = session('active_lab_id') ?? request()->session()->get('active_lab_id');

            if ($activeLabId && $laboran->laboratoriums->contains('id', $activeLabId)) {
                // Gunakan lab dari session jika valid
                $daftarLab = DaftarLab::findOrFail($activeLabId);
            } else {
                // Fallback ke lab pertama
                $daftarLab = $laboran->laboratoriums->first();
                if ($daftarLab) {
                    session(['active_lab_id' => $daftarLab->id]);
                    request()->session()->put('active_lab_id', $daftarLab->id);
                }
            }
        }

        // Debug: Pastikan lab yang digunakan benar
        Log::info('Dashboard - Active Lab', [
            'route_id' => $id,
            'active_lab_id' => $daftarLab->id ?? null,
            'active_lab_name' => $daftarLab->Nama_Laboratorium ?? null,
            'session_active_lab_id' => session('active_lab_id'),
        ]);

        if (! $daftarLab) {
            return redirect()->route('login')->with('error', 'Laboratorium tidak ditemukan');
        }

        // Statistik
        $peminjamanRuanganMenunggu = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
            ->where('status', 'menunggu')
            ->count();

        $peminjamanAlatMenunggu = PeminjamanAlat::where('daftar_lab_id', $daftarLab->id)->where('status', 'menunggu')->count();

        $totalPengumuman = Pengumuman::where('author', $user->Nama)
            ->where('status', 'publish')
            ->count();

        // Data untuk tabel
        $peminjamanRuangan = PeminjamanRuangan::where('daftar_lab_id', $daftarLab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAlat = PeminjamanAlat::where('daftar_lab_id', $daftarLab->id)->with('alatLab', 'riskAssessment')->orderBy('created_at', 'desc')->get();

        $pengumuman = Pengumuman::where('author', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua lab yang dikelola laboran untuk navbar
        $labs = $laboran->laboratoriums;

        return view('laboran.dashboard', compact(
            'laboran',
            'peminjamanRuanganMenunggu',
            'peminjamanAlatMenunggu',
            'totalPengumuman',
            'peminjamanRuangan',
            'peminjamanAlat',
            'pengumuman',
            'user',
            'labs',
            'daftarLab'
        ));
    }

    public function peminjamanRuangan($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        // Validasi bahwa lab ini dikelola oleh laboran
        if (! $laboran || ! $laboran->laboratoriums->contains('id', $id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laboratorium ini.');
        }

        // PERBAIKAN: Gunakan daftar_lab_id sesuai struktur database
        $peminjamanRuangan = PeminjamanRuangan::where('daftar_lab_id', $lab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Simpan lab aktif ke session
        session(['active_lab_id' => $id]);
        // Ambil semua lab yang dikelola laboran untuk navbar
        $labs = $laboran->laboratoriums;

        return view('laboran.peminjaman-ruangan', compact(
            'user',
            'laboran',
            'labs',
            'peminjamanRuangan',
            'lab'
        ));
    }

    public function peminjamanAlat($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        // Validasi bahwa lab ini dikelola oleh laboran
        if (! $laboran || ! $laboran->laboratoriums->contains('id', $id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laboratorium ini.');
        }

        // PERBAIKAN: Gunakan daftar_lab_id untuk visibilitas ketat
        $peminjamanAlat = PeminjamanAlat::with('alatLab')
            ->where('daftar_lab_id', $lab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Simpan lab aktif ke session
        session(['active_lab_id' => $id]);
        $labs = $laboran->laboratoriums;

        return view('laboran.peminjaman-alat', compact(
            'user',
            'laboran',
            'labs',
            'peminjamanAlat',
            'lab'
        ));
    }

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

    public function setujuiAlat(Request $request, $id)
    {
        $request->validate([
            'durasi_hari' => 'nullable|integer|min:1|max:365',
        ], [
            'durasi_hari.integer' => 'Durasi harus berupa angka.',
            'durasi_hari.min' => 'Durasi minimal 1 hari.',
            'durasi_hari.max' => 'Durasi maksimal 365 hari.',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = PeminjamanAlat::with('alatLab.daftarLab')->findOrFail($id);

            // Ambil data alat
            $alat = AlatLab::findOrFail($peminjaman->alat_lab_id);

            // Cek apakah stok tersedia
            if ($alat->jumlah_tersedia < $peminjaman->jumlah) {
                return redirect()->back()->with('error', 'Stok alat tidak mencukupi. Stok tersedia: '.$alat->jumlah_tersedia.', diminta: '.$peminjaman->jumlah);
            }

            // Update status peminjaman
            $peminjaman->status = 'disetujui';

            if ($request->filled('durasi_hari')) {
                $durasi = (int) $request->durasi_hari;
                $batasWaktu = Carbon::parse($peminjaman->tanggal_pinjam)->addDays($durasi);
                $peminjaman->batas_waktu_peminjaman = $batasWaktu;
                $peminjaman->tanggal_kembali = $batasWaktu->toDateString();
            }
            $peminjaman->save();

            // Kurangi stok alat sebanyak jumlah yang dipinjam
            $alat->jumlah_tersedia = $alat->jumlah_tersedia - $peminjaman->jumlah;
            $alat->save();

            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Menyetujui Peminjaman Alat',
                'description' => "Peminjaman alat {$alat->nama_alat} oleh {$peminjaman->user_nama} telah disetujui. Stok tersisa: {$alat->jumlah_tersedia}",
                'ip_address' => request()->ip(),
            ]);

            // ✅ KIRIM EMAIL KE MAHASISWA
            $mahasiswa = DaftarUser::where('Nama', $peminjaman->user_nama)->first();
            if ($mahasiswa && $mahasiswa->Email) {
                try {
                    Mail::to($mahasiswa->Email)->send(
                        new PeminjamanAlatMail($peminjaman, 'hasil_laboran')
                    );
                    Log::info('Email persetujuan peminjaman alat berhasil dikirim ke: '.$mahasiswa->Email);
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email ke mahasiswa: '.$e->getMessage());
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Peminjaman alat berhasil disetujui and email notifikasi telah dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyetujui peminjaman: '.$e->getMessage());
        }
    }

    public function bebasLab($id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($id);
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

        // Ambil requests dengan approval milik laboran yang login saja
        $requests = BebasLabRequest::with(['user', 'riskAssessment', 'approvals' => function ($query) use ($lab, $user) {
            $query->where('daftar_lab_id', $lab->id)
                ->where('laboran_user_id', $user->UserID);
        }])
            ->orderBy('created_at', 'desc')
            ->get();

        $equipmentByRequest = [];
        $roomByRequest = [];
        foreach ($requests as $req) {
            // Cek peminjaman alat yang terkait dengan mahasiswa ini dan RA ini
            $alat = PeminjamanAlat::with('alatLab')
                ->where('user_nama', $req->user_nama)
                ->where('risk_assessment_id', $req->risk_assessment_id)
                ->where('daftar_lab_id', $lab->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Cek peminjaman ruangan
            $ruangan = PeminjamanRuangan::where('user_nama', $req->user_nama)
                ->where('daftar_lab_id', $lab->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Ambil approval spesifik laboran ini
            $req->approval = $req->approvals->first();

            // Hitung tanggungan saat pengajuan
            $req->pending_alat_count_at_request = $alat
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where('created_at', '<=', $req->created_at)
                ->count();

            // Hitung tanggungan saat ini
            $req->pending_alat_count_now = $alat
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->count();

            $req->pending_ruangan_count_at_request = $ruangan
                ->whereIn('status', ['menunggu', 'menunggu_kepala_lab', 'disetujui'])
                ->where('created_at', '<=', $req->created_at)
                ->count();

            // Hitung tanggungan saat ini
            $req->pending_ruangan_count_now = $ruangan
                ->whereIn('status', ['menunggu', 'menunggu_kepala_lab', 'disetujui'])
                ->count();

            $equipmentByRequest[$req->id] = $alat;
            $roomByRequest[$req->id] = $ruangan;
        }

        // Ambil semua lab yang dikelola laboran untuk navbar
        $labs = $this->getLabsForUser($user);

        return view('laboran.bebas-lab', compact(
            'user',
            'laboran',
            'labs',
            'lab',
            'requests',
            'equipmentByRequest',
            'roomByRequest'
        ));
    }

    public function bebasLabDetail($labId, $requestId)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();

        $bebasLabRequest = BebasLabRequest::with(['user', 'riskAssessment', 'approvals' => function ($query) use ($lab, $user) {
            $query->where('daftar_lab_id', $lab->id)
                ->where('laboran_user_id', $user->UserID);
        }])
            ->findOrFail($requestId);

        $approval = $bebasLabRequest->approvals->first();

        // Data tanggungan alat
        $alatList = PeminjamanAlat::with('alatLab')
            ->where('user_nama', $bebasLabRequest->user_nama)
            ->where('risk_assessment_id', $bebasLabRequest->risk_assessment_id)
            ->where('daftar_lab_id', $lab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Data tanggungan ruangan
        $ruanganList = PeminjamanRuangan::where('user_nama', $bebasLabRequest->user_nama)
            ->where('daftar_lab_id', $lab->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingAlatNow = $alatList
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count();

        $pendingRuanganNow = $ruanganList
            ->whereIn('status', ['menunggu', 'menunggu_kepala_lab', 'disetujui'])
            ->count();

        $pendingNow = $pendingAlatNow + $pendingRuanganNow;

        // Ambil semua lab yang dikelola laboran untuk navbar
        $labs = $this->getLabsForUser($user);

        return view('laboran.bebas-lab-detail', compact(
            'user',
            'laboran',
            'labs',
            'lab',
            'bebasLabRequest',
            'approval',
            'alatList',
            'ruanganList',
            'pendingAlatNow',
            'pendingRuanganNow',
            'pendingNow'
        ));
    }

    public function setujuiBebasLab($labId, $requestId)
    {
        $user = Auth::user();
        $bebasLabRequest = BebasLabRequest::findOrFail($requestId);

        $approval = BebasLabApproval::where('bebas_lab_request_id', $requestId)
            ->where('daftar_lab_id', $labId)
            ->where('laboran_user_id', $user->UserID)
            ->first();

        if ($approval) {
            $approval->status = 'disetujui';
            $approval->laboran_nama = $user->Nama;
            $approval->approved_at = now();
            $approval->save();

            // Cek apakah ini approval terakhir
            if ($bebasLabRequest->isFullyApproved()) {
                $bebasLabRequest->status = 'disetujui';
                $bebasLabRequest->setMasaBerlaku(); // Set 6 bulan masa berlaku
                $bebasLabRequest->save();
            }

            return redirect()->back()->with('success', 'Bebas lab berhasil disetujui.');
        }

        return redirect()->back()->with('error', 'Data approval tidak ditemukan.');
    }

    public function tolakBebasLab(Request $request, $labId, $requestId)
    {
        $user = Auth::user();
        $approval = BebasLabApproval::where('bebas_lab_request_id', $requestId)
            ->where('daftar_lab_id', $labId)
            ->where('laboran_user_id', $user->UserID)
            ->first();

        if ($approval) {
            $approval->status = 'ditolak';
            $approval->catatan = $request->catatan;
            $approval->laboran_nama = $user->Nama;
            $approval->approved_at = now();
            $approval->save();

            return redirect()->back()->with('success', 'Bebas lab telah ditolak.');
        }

        return redirect()->back()->with('error', 'Data approval tidak ditemukan.');
    }

    public function downloadBebasLab($labId, $requestId)
    {
        $bebasLabRequest = BebasLabRequest::with(['user', 'approvals', 'riskAssessment.daftarLab'])->findOrFail($requestId);

        if (!$bebasLabRequest->isFullyApproved()) {
            return redirect()->back()->with('error', 'Pengajuan Bebas Lab belum disetujui oleh semua laboran.');
        }

        if (! $bebasLabRequest->is_active || ! $bebasLabRequest->isMasihBerlaku()) {
            return redirect()->back()->with('error', 'Bebas Lab sudah tidak aktif atau masa berlakunya habis.');
        }

        if ($bebasLabRequest->hasPeminjamanAktif()) {
            return redirect()->back()->with('error', 'Mahasiswa memiliki peminjaman aktif.');
        }

        $templatePath = storage_path('app/templates/bebas_lab.docx');
        App::setLocale('id');
        Carbon::setLocale('id');
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $phpWord->setValues([
            'NAMA_MAHASISWA' => $bebasLabRequest->user_nama,
            'NIM' => $bebasLabRequest->riskAssessment->nim ?? '-',
            'TEMPAT_LAB' => $bebasLabRequest->riskAssessment->daftarLab->Nama_Laboratorium ?? '-',
            'JUDUL_PENELITIAN' => $bebasLabRequest->riskAssessment->topik_judul ?? '-',
        ]);

        $approvals = $bebasLabRequest->approvals;
        if ($approvals->isEmpty()) {
            $phpWord->setValue('NOMOR', '-');
            $phpWord->setValue('LAB_NAME', '-');
            $phpWord->setValue('LABORAN_NAME', '-');
            $phpWord->setValue('PERSETUJUAN', '-');
        } else {
            $phpWord->cloneRow('NOMOR', $approvals->count());
            foreach ($approvals as $index => $approval) {
                $rowNumber = $index + 1;
                $phpWord->setValue('NOMOR#'.$rowNumber, (string) $rowNumber);
                $phpWord->setValue('LAB_NAME#'.$rowNumber, $approval->lab->Nama_Laboratorium ?? '-');
                $phpWord->setValue('LABORAN_NAME#'.$rowNumber, $approval->laboran_nama ?? '-');
                $persetujuan = $approval->status === 'disetujui' ? '✓ Disetujui' : 'Menunggu';
                $phpWord->setValue('PERSETUJUAN#'.$rowNumber, $persetujuan);
            }
        }

        // Kepala lab - mendukung multi-role
        $kepalaLabs = DaftarUser::where(function ($query) {
            $query->where('Role_User', 'Kepala Laboratorium')
                ->orWhereHas('roles', fn ($q) => $q->where('name', 'Kepala Laboratorium'));
        })->get()->unique('id');

        for ($i = 1; $i <= 2; $i++) {
            if ($kepalaLabs->count() >= $i) {
                $kepalaLab = $kepalaLabs->values()[$i - 1];
                $phpWord->setValue('KEPALA_LAB_'.$i, $kepalaLab->Nama ?? '-');
                $phpWord->setValue('NO_IDENTITAS_'.$i, $kepalaLab->nomor_identitas ?? '-');
                $phpWord->setValue('PERSETUJUAN_KEPALA_LAB_'.$i, 'Disetujui');
            } else {
                $phpWord->setValue('KEPALA_LAB_'.$i, '-');
                $phpWord->setValue('NO_IDENTITAS_'.$i, '-');
                $phpWord->setValue('PERSETUJUAN_KEPALA_LAB_'.$i, '-');
            }
        }

        $fileName = 'Bebas_Lab_'.$bebasLabRequest->id.'.docx';
        $savePath = storage_path('app/public/'.$fileName);
        $phpWord->saveAs($savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    public function tolakAlat($id)
    {
        $peminjaman = PeminjamanAlat::with('alatLab.daftarLab')->findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();
        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menolak Peminjaman Alat',
            'description' => "Peminjaman alat oleh {$peminjaman->user_nama} telah ditolak",
            'ip_address' => request()->ip(),
        ]);
        return redirect()->back()->with('success', 'Peminjaman alat ditolak.');
    }

    public function setujuiRuangan(Request $request, $id)
    {
        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);
        $peminjaman->status = 'menunggu_kepala_lab';
        $peminjaman->laboran_id = Auth::id();
        $peminjaman->persetujuan_laboran = true;
        $peminjaman->catatan_laboran = $request->catatan;
        $peminjaman->tanggal_persetujuan_laboran = now();
        if ($peminjaman->save()) {
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Menyetujui Peminjaman Ruangan',
                'description' => "Peminjaman ruangan oleh {$peminjaman->user_nama} telah disetujui",
                'ip_address' => request()->ip(),
            ]);
            return back()->with('success', 'Peminjaman ruangan disetujui laboran.');
        }
        return back()->with('error', 'Gagal.');
    }

    public function tolakRuangan($id)
    {
        $peminjaman = PeminjamanRuangan::with('daftarLab')->findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->laboran_id = Auth::id();
        $peminjaman->persetujuan_laboran = false;
        $peminjaman->tanggal_persetujuan_laboran = now();
        $peminjaman->save();
        ActivityLog::create([
            'user_name' => Auth::user()->Nama,
            'action' => 'Menolak Peminjaman Ruangan',
            'description' => "Peminjaman oleh {$peminjaman->user_nama} ditolak",
            'ip_address' => request()->ip(),
        ]);
        return redirect()->back()->with('success', 'Peminjaman ruangan ditolak.');
    }

    public function kembalikanRuangan($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = PeminjamanRuangan::findOrFail($id);
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_kembali = now();
            $peminjaman->save();
            ActivityLog::create([
                'user_name' => Auth::user()->Nama,
                'action' => 'Menandai Ruangan Dikembalikan',
                'description' => "Ruangan dari peminjaman oleh {$peminjaman->user_nama} telah dikembalikan",
                'ip_address' => request()->ip(),
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Ruangan dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function kembalikanAlat($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = PeminjamanAlat::findOrFail($id);
            $alat = AlatLab::findOrFail($peminjaman->alat_lab_id);
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_kembali = now();
            $peminjaman->save();
            $alat->jumlah_tersedia = $alat->jumlah_tersedia + $peminjaman->jumlah;
            $alat->save();
            DB::commit();
            return redirect()->back()->with('success', 'Alat dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function setujuiPenelitian($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        $penelitian->status = 'disetujui';
        $penelitian->save();
        return redirect()->back()->with('success', 'Penelitian disetujui');
    }

    public function tolakPenelitian($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        $penelitian->status = 'ditolak';
        $penelitian->save();
        return redirect()->back()->with('success', 'Penelitian ditolak');
    }

    public function createPengumuman()
    {
        $labs = DaftarLab::all();
        return view('laboran.pengumuman-create', ['user' => Auth::user(), 'labs' => $labs]);
    }

    public function storePengumuman(Request $request)
    {
        $validated = $request->validate(['judul' => 'required|string', 'isi' => 'required|string', 'status' => 'required']);
        $validated['author'] = Auth::user()->Nama;
        Pengumuman::create($validated);
        return back()->with('success', 'Pengumuman dibuat');
    }

    public function editPengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('laboran.pengumuman-edit', ['pengumuman' => $pengumuman, 'user' => Auth::user(), 'labs' => DaftarLab::all()]);
    }

    public function updatePengumuman(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->validate(['judul' => 'required', 'isi' => 'required', 'status' => 'required']));
        return back()->with('success', 'Pengumuman diupdate');
    }

    public function destroyPengumuman($id)
    {
        Pengumuman::findOrFail($id)->delete();
        return back()->with('success', 'Pengumuman dihapus');
    }

    public function indexAlat($lab_id)
    {
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($lab_id);
        $alats = AlatLab::where('stock_group_id', $lab->stock_group_id)
            ->where(function ($query) use ($lab_id) {
                $query->whereNull('daftar_lab_id')->orWhere('daftar_lab_id', $lab_id);
            })->paginate(10);
        $labs = $this->getLabsForUser($user);
        return view('laboran.alat.index', compact('alats', 'lab', 'user', 'labs'));
    }

    public function storeAlat(Request $request, $lab_id)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'scope' => 'required|in:this_lab,all_labs',
        ]);
        $filename = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);
        }
        $lab = DaftarLab::findOrFail($lab_id);
        $daftar_lab_id = $request->scope === 'all_labs' ? null : $lab_id;
        AlatLab::create([
            'daftar_lab_id' => $daftar_lab_id,
            'stock_group_id' => $lab->stock_group_id,
            'nama_alat' => $request->nama_alat,
            'jumlah_total' => $request->jumlah,
            'jumlah_tersedia' => $request->jumlah,
            'deskripsi' => $request->deskripsi,
            'foto' => $filename,
        ]);
        return back()->with('success', 'Alat berhasil ditambahkan.');
    }

    public function updateAlat(Request $request, $lab_id, $alat_id)
    {
        $lab = DaftarLab::findOrFail($lab_id);
        $alat = AlatLab::where('stock_group_id', $lab->stock_group_id)->findOrFail($alat_id);
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'scope' => 'required|in:this_lab,all_labs',
        ]);
        $daftar_lab_id = $request->scope === 'all_labs' ? null : $lab_id;
        $data = [
            'nama_alat' => $request->nama_alat,
            'jumlah_total' => $request->jumlah,
            'jumlah_tersedia' => $request->jumlah,
            'deskripsi' => $request->deskripsi,
            'daftar_lab_id' => $daftar_lab_id,
        ];
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);
            $data['foto'] = $filename;
        }
        $alat->update($data);
        return back()->with('success', 'Data alat diperbarui!');
    }

    public function destroyAlat($lab_id, $alat_id)
    {
        $lab = DaftarLab::findOrFail($lab_id);
        $alat = AlatLab::where('stock_group_id', $lab->stock_group_id)->findOrFail($alat_id);
        $alat->delete();
        return back()->with('success', 'Alat dihapus.');
    }

    private function getLabsForUser($user)
    {
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();
        return $laboran ? $laboran->laboratoriums : collect();
    }

    public function riskAssessment($id)
    {
        $user = Auth::user();
        $laboran = DaftarLaboranLaboratorium::where('UserID', $user->UserID)->first();
        $riskAssessments = RiskAssessment::with(['user', 'daftarLab'])->orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();
        $lab = $laboran->daftarLab ?? DaftarLab::find($id);
        return view('laboran.risk-assessment', compact('user', 'laboran', 'labs', 'riskAssessments', 'lab'));
    }

    public function detailRiskAssessment($id)
    {
        $riskAssessment = RiskAssessment::with(['user', 'daftarLab', 'bahanKimias', 'peralatanOperasi', 'pelakuKerja', 'pernyataanMahasiswa'])->findOrFail($id);
        return view('laboran.detail-risk-assessment', ['riskAssessment' => $riskAssessment, 'user' => Auth::user(), 'laboran' => DaftarLaboranLaboratorium::where('UserID', Auth::user()->UserID)->first()]);
    }

    public function profil()
    {
        return view('laboran.profil', ['user' => Auth::user(), 'labs' => DaftarLab::all()]);
    }

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


    public function downloadRiskAssessment($id, $format = 'pdf')
    {
        $riskAssessment = RiskAssessment::with([
            'user', 'daftarLab', 'dosenPembimbing', 'safetyOfficer', 'kepalaLab', 'bahanKimias', 
            'kategoriHazardBahan', 'peralatanOperasi', 'pelakuKerja', 'pernyataanMahasiswa'
        ])->findOrFail($id);

        $templatePath = storage_path('app/templates/template1.docx');
        App::setLocale('id');
        Carbon::setLocale('id');
        CarbonImmutable::setLocale('id');
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $current_time = Carbon::now('Asia/Jakarta')->translatedFormat(('d F Y'));
        $formatDate = fn ($value) => $value ? Carbon::parse($value)->translatedFormat('d F Y') : '-';

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
            'KATEGORI_RISIKO_DOSEN' => ucwords(str_replace('_', ' ', $riskAssessment->kategori_resiko_dosen ?? '-')),
            'NAMA_SO' => $riskAssessment->safety_officer_nama ?? '-',
            'TGL_PERSETUJUAN_DOSEN' => $formatDate($riskAssessment->tanggal_persetujuan_dosen),
            'TGL_PERSETUJUAN_KLAB' => $formatDate($riskAssessment->tanggal_persetujuan_kepala_lab),
            'TGL_PERSETUJUAN_SO' => $formatDate($riskAssessment->tanggal_persetujuan_safety_officer),
            'NAMA_LAB' => $riskAssessment->daftarLab?->Nama_Laboratorium ?? '-',
            'PERSETUJUAN_MAHASISWA' => 'Disetujui',
        ]);

        foreach (['KAPRODI', 'KEPALA_LAB', 'SAFETY_OFFICER', 'DOSEN'] as $key) {
            $field = 'persetujuan_' . strtolower($key);
            $phpWord->setValue('PERSETUJUAN_' . $key, $riskAssessment->$field == 1 ? 'Disetujui' : 'Tidak Disetujui');
        }

        $bahanList = $riskAssessment->bahanKimias;
        for ($i = 1; $i <= 10; $i++) {
            $bahan = $bahanList[$i - 1] ?? null;
            $cek = fn ($v) => $v == 1 ? '✓' : '';
            $phpWord->setValue("BAHAN_KIMIA_{$i}", $bahan->nama_bahan ?? '');
            foreach (['explosive', 'flammable', 'toxic', 'corrosive', 'irritant', 'oxidizing'] as $attr) {
                $phpWord->setValue(strtoupper($attr)."_{$i}", $bahan ? $cek($bahan->$attr) : '');
            }
        }

        $fileName = 'Risk_Assessment_'.$riskAssessment->id.'.docx';
        $savePath = storage_path('app/public/'.$fileName);
        $phpWord->saveAs($savePath);
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    public function sendDeadlineNotification($id)
    {
        try {
            $riskAssessment = RiskAssessment::findOrFail($id);
            Mail::to($riskAssessment->user->Email)->send(new RiskAssessmentMail($riskAssessment, 'perpanjangan_deadline'));
            return back()->with('success', 'Sent.');
        } catch (\Exception $e) { return back()->with('error', $e->getMessage()); }
    }

    public function getRiskAssessmentWithKaprodiStatus($id)
    {
        $riskAssessment = RiskAssessment::with(['user', 'dosenPembimbing', 'safetyOfficer', 'kepalaLab', 'kaprodi'])->findOrFail($id);
        return response()->json($riskAssessment);
    }
}
