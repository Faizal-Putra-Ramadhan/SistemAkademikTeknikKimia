<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanAlatMail;
use App\Mail\PeminjamanRuanganMail;
use App\Models\ActivityLog;
use App\Models\AlatLab;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanRuangan;
use App\Models\PengajuanPenelitian;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * Dashboard Dosen
     */
    // public function dashboard()
    // {
    //     $user = Auth::user();

    //     // Ambil pengajuan penelitian yang perlu disetujui dosen
    //     $pengajuanPenelitian = PengajuanPenelitian::where('dosen_pembimbing', $user->Nama)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     // Statistik
    //     $totalPengajuan = $pengajuanPenelitian->count();
    //     $menungguPersetujuan = $pengajuanPenelitian->where('status', 'menunggu')->count();
    //     $disetujui = $pengajuanPenelitian->where('status', 'disetujui')->count();
    //     $ditolak = $pengajuanPenelitian->where('status', 'ditolak')->count();

    //     // Peminjaman dosen sendiri
    //     $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
    //         ->with('alatLab')
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     return view('dosen.dashboard', compact(
    //         'user',
    //         'pengajuanPenelitian',
    //         'totalPengajuan',
    //         'menungguPersetujuan',
    //         'disetujui',
    //         'ditolak',
    //         'peminjamanRuangan',
    //         'peminjamanAlat'
    //     ));
    // }

    public function dashboard()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        $riskAssessments = RiskAssessment::where('dosen_pembimbing_id', $user->id)
            ->with('daftarLab')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ambil pengajuan penelitian yang perlu disetujui dosen
        // $pengajuanPenelitian = PengajuanPenelitian::where('dosen_pembimbing', $user->Nama)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        // Statistik
        // $totalPengajuan = $pengajuanPenelitian->count();
        // $menungguPersetujuan = $pengajuanPenelitian->where('status', 'menunggu')->count();
        // $disetujui = $pengajuanPenelitian->where('status', 'disetujui')->count();
        // $ditolak = $pengajuanPenelitian->where('status', 'ditolak')->count();

        // Peminjaman dosen sendiri
        $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
            ->with('alatLab', 'riskAssessment')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'user',
            // 'pengajuanPenelitian',
            // 'totalPengajuan',
            // 'menungguPersetujuan',
            // 'disetujui',
            // 'ditolak',
            'peminjamanRuangan',
            'peminjamanAlat',
            'labs',
            'riskAssessments'
        ));
    }

    /**
     * Detail Pengajuan Penelitian
     */
    // public function detailPengajuanPenelitian($id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::with('daftarLab')->findOrFail($id);

    //     // Pastikan dosen ini adalah pembimbing dari penelitian ini
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         abort(403, 'Anda bukan pembimbing dari penelitian ini.');
    //     }

    //     return view('dosen.detail-pengajuan', compact('pengajuan', 'user'));
    // }

    // public function detailPengajuanPenelitian($id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::with('daftarLab')->findOrFail($id);
    //     $labs = DaftarLab::all();

    //     // Pastikan dosen ini adalah pembimbing dari penelitian ini
    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         abort(403, 'Anda bukan pembimbing dari penelitian ini.');
    //     }

    //     return view('dosen.detail-pengajuan', compact('pengajuan', 'user', 'labs'));
    // }

    /**
     * Setujui Pengajuan Penelitian
     */
    // public function setujuiPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);

    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'disetujui']);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menyetujui Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian berhasil disetujui!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
    //     }
    // }

    // public function setujuiPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);

    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'disetujui']);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menyetujui Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian berhasil disetujui!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
    //     }
    // }

    /**
     * Tolak Pengajuan Penelitian
     */
    // public function tolakPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);

    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'ditolak']);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menolak Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian ditolak.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
    //     }
    // }

    // public function tolakPengajuan(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $pengajuan = PengajuanPenelitian::findOrFail($id);

    //     if ($pengajuan->dosen_pembimbing !== $user->Nama) {
    //         return back()->with('error', 'Anda tidak memiliki akses.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $pengajuan->update(['status' => 'ditolak']);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Menolak Pengajuan Penelitian',
    //             'description' => "Penelitian: {$pengajuan->judul_penelitian} oleh {$pengajuan->user_nama}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Pengajuan penelitian ditolak.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
    //     }
    // }

    /**
     * Daftar Lab untuk Peminjaman
     */
    public function daftarLab()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('dosen.daftar-lab', compact('labs', 'user'));
    }

    /**
     * Form Peminjaman Ruangan
     */
    // public function formPeminjamanRuangan($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();

    //     return view('dosen.pinjam-ruangan', compact('lab', 'user'));
    // }

    public function formPeminjamanRuangan($labId)
    {
        $lab = DaftarLab::find($labId);
        $user = Auth::user();
        $labs = DaftarLab::all();
        $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        return view('dosen.pinjam-ruangan', compact('lab', 'user', 'labs', 'peminjaman_ruangans'));
    }

    /**
     * Simpan Peminjaman Ruangan
     */
    // public function storePeminjamanRuangan(Request $request, $labId)
    // {
    //     $request->validate([
    //         'tanggal' => 'required|date|after_or_equal:today',
    //         'jam_mulai' => 'required',
    //         'jam_selesai' => 'required|after:jam_mulai',
    //         'keperluan' => 'required|string|max:500',
    //     ]);

    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     DB::beginTransaction();
    //     try {
    //         PeminjamanRuangan::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'tanggal' => $request->tanggal,
    //             'jam_mulai' => $request->jam_mulai,
    //             'jam_selesai' => $request->jam_selesai,
    //             'keperluan' => $request->keperluan,
    //             'status' => 'menunggu',
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Mengajukan Peminjaman Ruangan',
    //             'description' => "Lab: {$lab->Nama_Laboratorium}, Tanggal: {$request->tanggal}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.dashboard')
    //             ->with('success', 'Peminjaman ruangan berhasil diajukan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    //     }
    // }

    public function storePeminjamanRuangan(Request $request, $labId)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keperluan' => 'required|string|max:500',
        ], [
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $tanggalMulai = $request->input('tanggal');
            $tanggalSelesai = $request->input('tanggal_selesai', $tanggalMulai);
            $jamMulai = $request->input('jam_mulai');
            $jamSelesai = $request->input('jam_selesai');

            if ($jamMulai && $jamSelesai) {
                $sameDate = $tanggalMulai && $tanggalSelesai && $tanggalMulai === $tanggalSelesai;

                if ($sameDate && $jamSelesai < $jamMulai) {
                    $validator->errors()->add('jam_selesai', 'Jam selesai tidak boleh lebih kecil dari jam mulai jika tanggal sama.');
                }
            }
        });

        $validator->validate();

        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);

        // Cek ketersediaan ruangan
        $konflik = $this->cekKetersediaanRuangan(
            $labId,
            $request->tanggal,
            $request->tanggal_selesai,
            $request->jam_mulai,
            $request->jam_selesai
        );

        if ($konflik) {
            return back()
                ->withInput()
                ->with('error', 'Ruangan sedang dipakai pada tanggal dan jam tersebut. Mohon tunggu sampai ruangan sudah tidak dipinjam atau pilih waktu lain.');
        }

        DB::beginTransaction();
        try {
            $peminjaman = PeminjamanRuangan::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'tanggal' => $request->tanggal,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengajukan Peminjaman Ruangan',
                'description' => "Lab: {$lab->Nama_Laboratorium}, Tanggal: {$request->tanggal} - {$request->tanggal_selesai}",
                'ip_address' => $request->ip(),
            ]);

            // ✅ KIRIM EMAIL KE LABORAN (mendukung multi-role)
            $laborans = DaftarUser::withLaboranRole()
                ->whereHas('laborans', function ($q) use ($lab) {
                $q->where('Laboratorium', $lab->Nama_Laboratorium);
            })
                ->get();

            foreach ($laborans as $laboran) {
                if ($laboran && $laboran->Email) {
                    try {
                        Mail::to($laboran->Email)->send(
                            new PeminjamanRuanganMail($peminjaman->load('daftarLab'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman ruangan (dosen) berhasil dikirim ke laboran: ' . $laboran->Email);
                    }
                    catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke laboran: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return redirect()->route('dosen.dashboard')
                ->with('success', 'Peminjaman ruangan berhasil diajukan! Email notifikasi telah dikirim ke laboran.');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Form Peminjaman Alat
     */
    // public function formPeminjamanAlat($labId)
    // {
    //     $lab = DaftarLab::with('alatLabs')->findOrFail($labId);
    //     $user = Auth::user();

    //     return view('dosen.pinjam-alat', compact('lab', 'user'));
    // }

    public function formPeminjamanAlat($labId)
    {
        $lab = DaftarLab::with('alatLabs')->find($labId); // Jangan pakai findOrFail
        $user = Auth::user();
        $labs = DaftarLab::all();

        if (!$lab) {
            // Jika lab tidak ditemukan, tampilkan view dengan data kosong dan pesan user-friendly
            return view('dosen.pinjam-alat', [
                'lab' => null,
                'user' => $user,
                'labs' => $labs,
                'riskAssessments' => collect(),
                'riskAssessment' => null,
                'peminjaman_alats' => collect(),
                'pesan' => 'Belum ada laboratorium yang tersedia atau lab tidak ditemukan.',
            ]);
        }

        if (!$lab->stock_group_id) {
            return view('dosen.pinjam-alat', [
                'lab' => $lab,
                'user' => $user,
                'labs' => $labs,
                'riskAssessments' => collect(),
                'riskAssessment' => null,
                'peminjaman_alats' => collect(),
                'pesan' => 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.',
            ]);
        }

        // Ambil SEMUA Risk Assessment yang disetujui (tidak difilter berdasarkan lab)
        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->get();

        // Generate ID RA untuk yang belum punya
        foreach ($riskAssessments as $ra) {
            if (!$ra->id_ra) {
                $ra->generateIdRa();
            }
        }

        $riskAssessment = $riskAssessments->first();

        $peminjaman_alats = PeminjamanAlat::with('alatLab', 'riskAssessment')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        return view('dosen.pinjam-alat', compact('lab', 'user', 'labs', 'riskAssessment', 'riskAssessments', 'peminjaman_alats'));
    }

    /**
     * Simpan Peminjaman Alat
     */
    // public function storePeminjamanAlat(Request $request, $labId)
    // {
    //     $request->validate([
    //         'alat_lab_id' => 'required|exists:alat_labs,id',
    //         'tanggal_pinjam' => 'required|date|after_or_equal:today',
    //         'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
    //     ]);

    //     $user = Auth::user();
    //     $alat = AlatLab::findOrFail($request->alat_lab_id);

    //     if ($alat->jumlah_tersedia <= 0) {
    //         return back()->with('error', 'Alat tidak tersedia!');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         PeminjamanAlat::create([
    //             'user_nama' => $user->Nama,
    //             'alat_lab_id' => $request->alat_lab_id,
    //             'tanggal_pinjam' => $request->tanggal_pinjam,
    //             'tanggal_kembali' => $request->tanggal_kembali,
    //             'status' => 'menunggu',
    //         ]);

    //         // Log aktivitas
    //         ActivityLog::create([
    //             'user_name' => $user->Nama,
    //             'action' => 'Mengajukan Peminjaman Alat',
    //             'description' => "Alat: {$alat->nama_alat}",
    //             'ip_address' => $request->ip(),
    //         ]);

    //         DB::commit();
    //         return redirect()->route('dosen.dashboard')
    //             ->with('success', 'Peminjaman alat berhasil diajukan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    //     }
    // }

    public function storePeminjamanAlat(Request $request, $labId)
    {
        $user = Auth::user();

        // VALIDASI: Hanya alat dan tanggal
        $request->validate([
            'alat_lab_id' => 'required|exists:alat_labs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        $lab = DaftarLab::findOrFail($labId);
        $alat = AlatLab::findOrFail($request->alat_lab_id);

        if (!$lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        if ((int)$alat->stock_group_id !== (int)$lab->stock_group_id) {
            return back()->with('error', 'Alat tidak valid untuk grup stok laboratorium ini.');
        }

        // VALIDASI: Cek apakah alat spesifik untuk lab lain
        if ($alat->daftar_lab_id && (int)$alat->daftar_lab_id !== (int)$lab->id) {
            return back()->with('error', 'Alat ini hanya tersedia di ' . ($alat->daftarLab->Nama_Laboratorium ?? 'lab lain') . '.');
        }
        if ($alat->jumlah_tersedia < $request->jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi!');
        }

        DB::beginTransaction();
        try {
            $peminjaman = PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'alat_lab_id' => $request->alat_lab_id,
                'daftar_lab_id' => $labId,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => $user->Nama,
                'action' => 'Mengajukan Peminjaman Alat',
                'description' => "Alat: {$alat->nama_alat}",
                'ip_address' => $request->ip(),
            ]);

            // ✅ KIRIM EMAIL KE LABORAN (mendukung multi-role)
            $laborans = DaftarUser::withLaboranRole()
                ->whereHas('laborans', function ($q) use ($lab) {
                $q->where('Laboratorium', $lab->Nama_Laboratorium);
            })
                ->get();

            foreach ($laborans as $laboran) {
                if ($laboran && $laboran->Email) {
                    try {
                        Mail::to($laboran->Email)->send(
                            new PeminjamanAlatMail($peminjaman->load('alatLab.daftarLab'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman alat (dosen) berhasil dikirim ke laboran: ' . $laboran->Email);
                    }
                    catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke laboran: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return redirect()->route('dosen.dashboard')
                ->with('success', 'Peminjaman alat berhasil diajukan!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Daftar Pengumuman
     */
    // public function pengumuman()
    // {
    //     $user = Auth::user();
    //     $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();

    //     return view('dosen.pengumuman.index', compact('pengumuman', 'user'));
    // }

    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();
        $labs = DaftarLab::all();

        return view('dosen.pengumuman.index', compact('pengumuman', 'user', 'labs'));
    }

    /**
     * Profil Dosen
     */
    // public function profil()
    // {
    //     $user = Auth::user();
    //     return view('dosen.profil', compact('user'));
    // }

    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::all();

        return view('dosen.profil', compact('user', 'labs'));
    }

    /**
     * Update Profil
     */
    // public function updateProfil(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'Nama' => 'required|string|max:255',
    //         'Phone' => 'required|string|max:20',
    //         'Email' => 'required|email|unique:daftar_users,Email,' . $user->id,
    //         'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $data = [
    //             'Nama' => $request->Nama,
    //             'Phone' => $request->Phone,
    //             'Email' => $request->Email,
    //         ];

    //         // Upload foto jika ada
    //         if ($request->hasFile('foto')) {
    //             $file = $request->file('foto');
    //             $filename = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('uploads/profile'), $filename);
    //             $data['foto'] = $filename;

    //             // Hapus foto lama jika ada
    //             if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
    //                 unlink(public_path('uploads/profile/' . $user->foto));
    //             }
    //         }

    //         $user->update($data);

    //         DB::commit();
    //         return back()->with('success', 'Profil berhasil diupdate!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
    //     }
    // }

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

            DB::commit();

            return back()->with('success', 'Profil berhasil diupdate!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
        }
    }

    /**
     * Cek apakah ruangan tersedia pada tanggal dan jam tertentu
     *
     * @param  int  $labId
     * @param  string  $tanggalMulai
     * @param  string  $tanggalSelesai
     * @param  string  $jamMulai
     * @param  string  $jamSelesai
     * @return bool true jika ada konflik (tidak tersedia), false jika tersedia
     */
    private function cekKetersediaanRuangan($labId, $tanggalMulai, $tanggalSelesai, $jamMulai, $jamSelesai)
    {
        // Cari peminjaman yang sudah disetujui atau menunggu untuk lab yang sama
        $peminjamanKonflik = PeminjamanRuangan::where('daftar_lab_id', $labId)
            ->whereIn('status', ['menunggu', 'disetujui_laboran', 'menunggu_kaprodi', 'disetujui'])
            ->where(function ($query) use ($tanggalMulai, $tanggalSelesai) {
            // Cek apakah ada overlap tanggal
            $query->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    // Kasus 1: Peminjaman baru dimulai di tengah-tengah peminjaman yang ada
                    $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                        ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                        // Kasus 2: Peminjaman yang ada berada di tengah-tengah peminjaman baru
                        ->orWhere(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                    $q2->where('tanggal', '<=', $tanggalMulai)
                        ->where('tanggal_selesai', '>=', $tanggalSelesai);
                }
                )
                    // Kasus 3: Peminjaman baru mencakup peminjaman yang ada
                    ->orWhere(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                    $q2->where('tanggal', '>=', $tanggalMulai)
                        ->where('tanggal_selesai', '<=', $tanggalSelesai);
                }
                );
            }
            );
        })
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
            // Cek apakah ada overlap jam
            $query->where(function ($q) use ($jamMulai, $jamSelesai) {
                    // Kasus 1: Jam mulai baru di antara jam yang ada
                    $q->where(function ($q2) use ($jamMulai) {
                            $q2->where('jam_mulai', '<=', $jamMulai)
                                ->where('jam_selesai', '>', $jamMulai);
                        }
                        )
                            // Kasus 2: Jam selesai baru di antara jam yang ada
                            ->orWhere(function ($q2) use ($jamSelesai) {
                    $q2->where('jam_mulai', '<', $jamSelesai)
                        ->where('jam_selesai', '>=', $jamSelesai);
                }
                )
                    // Kasus 3: Jam baru mencakup jam yang ada
                    ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                    $q2->where('jam_mulai', '>=', $jamMulai)
                        ->where('jam_selesai', '<=', $jamSelesai);
                }
                )
                    // Kasus 4: Jam yang ada mencakup jam baru
                    ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                    $q2->where('jam_mulai', '<=', $jamMulai)
                        ->where('jam_selesai', '>=', $jamSelesai);
                }
                );
            }
            );
        })
            ->exists();

        return $peminjamanKonflik;
    }
}
