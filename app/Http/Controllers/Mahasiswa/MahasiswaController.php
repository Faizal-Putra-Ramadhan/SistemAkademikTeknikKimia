<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanAlatMail;
use App\Models\AktivitasMahasiswa;
use App\Models\AlatLab;
use App\Models\BebasLabRequest;
use App\Models\DaftarLab;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanRuangan;
use App\Models\PengajuanPenelitian;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Dashboard Mahasiswa - Menampilkan semua lab
     */
    // public function dashboard()
    // {
    //     $labs = DaftarLab::penelitian()->get();
    //     $user = Auth::user();

    //     return view('mahasiswa.dashboard', compact('labs', 'user'));
    // }

    public function dashboard()
    {
        $labs = DaftarLab::where('lab_type', 'penelitian')->get();
        $user = Auth::user();
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.dashboard', compact('labs', 'user', 'pengumuman'));
    }

    /**
     * Detail Lab - Menampilkan info lab dan alat yang tersedia
     */
    public function detailLab($id)
    {
        $lab = DaftarLab::where('id', $id)->where('lab_type', 'penelitian')->firstOrFail();
        $user = Auth::user();

        return view('mahasiswa.detail-lab', compact('lab', 'user'));
    }

    /**
     * Form Peminjaman Ruangan
     */
    // public function formPeminjamanRuangan($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();

    //     return view('mahasiswa.pinjam-ruangan', compact('lab', 'user'));
    // }

    public function formPeminjamanRuangan($labId)
    {
        $lab = DaftarLab::findOrFail($labId);
        $user = Auth::user();
        $labs = DaftarLab::all();

        $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        return view('mahasiswa.pinjam-ruangan', compact('lab', 'user', 'labs', 'peminjaman_ruangans'));
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
    //         // Simpan peminjaman ruangan
    //         $peminjaman = PeminjamanRuangan::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'tanggal' => $request->tanggal,
    //             'jam_mulai' => $request->jam_mulai,
    //             'jam_selesai' => $request->jam_selesai,
    //             'keperluan' => $request->keperluan,
    //             'status' => 'menunggu',
    //         ]);

    //         $peminjaman_ruangans = PeminjamanRuangan::with('daftarLab')
    // ->where('user_nama', $user->Nama)
    // ->latest()
    // ->get();

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Peminjaman Ruangan',
    //             'keterangan' => "Mengajukan peminjaman ruangan {$lab->Nama_Laboratorium} pada {$request->tanggal}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keperluan' => 'required|string|max:500',
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

        $labs = DaftarLab::all();
        $user = Auth::user();
        $lab = DaftarLab::findOrFail($labId);

        DB::beginTransaction();
        try {
            // Simpan peminjaman ruangan
            $peminjaman = PeminjamanRuangan::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
            ]);

            // Catat aktivitas
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $labId,
                'jenis_aktivitas' => 'Peminjaman Ruangan',
                'keterangan' => "Mengajukan peminjaman ruangan {$lab->Nama_Laboratorium} pada {$request->tanggal}",
            ]);

            DB::commit();

            return redirect()->route('mahasiswa.aktivitas', $labId)
                ->with('success', 'Peminjaman ruangan berhasil diajukan!');
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

    //     return view('mahasiswa.pinjam-alat', compact('lab', 'user'));
    // }

    public function formPeminjamanAlat($labId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $labs = DaftarLab::penelitian()->get();

        // Kalau belum ada lab sama sekali
        if ($labs->isEmpty()) {
            return view('mahasiswa.pinjam-alat', [
                'lab' => null,
                'user' => $user,
                'labs' => $labs,
                'peminjaman' => collect(),
                'riskAssessments' => collect(),
                'riskAssessment' => null,
                'masihBerlaku' => false,
                'pesanBatasWaktu' => 'Belum ada laboratorium yang tersedia saat ini.',
                'sisaWaktu' => null,
                'sudahExpired' => false,
                'bebasLabByRa' => (object)[],
            ]);
        }

        // Jangan pakai findOrFail
        $lab = DaftarLab::with('alatLabs')->find($labId);

        if (!$lab) {
            return redirect()->route('dashboard')
                ->with('error', 'Lab tidak ditemukan.');
        }

        if ($lab->lab_type !== 'penelitian') {
            $firstResearchLab = $labs->first();
            if ($firstResearchLab) {
                return redirect()->route('mahasiswa.pinjam-alat', $firstResearchLab->id);
            }
            return redirect()->back()->with('error', 'Mahasiswa hanya dapat meminjam alat dari lab penelitian.');
        }

        if (!$lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'disetujui_final'])
            ->get();

        // Generate ID RA untuk yang belum punya
        foreach ($riskAssessments as $ra) {
            if (!$ra->id_ra) {
                $ra->generateIdRa();
            }
        }

        // Pilih Risk Assessment utama untuk pesan (prioritaskan yang masih berlaku)
        $validRiskAssessments = $riskAssessments->filter(function ($ra) {
            return $ra->isMasihBerlaku();
        });

        $riskAssessment = $validRiskAssessments->sortByDesc('batas_waktu_peminjaman')->first()
            ?? $riskAssessments->sortByDesc('batas_waktu_peminjaman')->first();

        // Validasi batas waktu peminjaman
        $masihBerlaku = false;
        $pesanBatasWaktu = null;
        $sisaWaktu = null;
        $sudahExpired = false;

        if ($riskAssessment) {
            // Cek apakah masih dalam periode pengajuan (4 bulan dari persetujuan)
            $masihBerlaku = $validRiskAssessments->isNotEmpty();

            if (!$masihBerlaku) {
                $sudahExpired = true;
                $pesanBatasWaktu = 'Batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir. '
                    . 'Terakhir bisa mengajukan: ' . $riskAssessment->getBatasWaktuPeminjamanFormatted()
                    . '. Silakan hubungi Kaprodi untuk perpanjangan.';
            }
            else {
                $sisaWaktu = $riskAssessment->getSisaWaktuPeminjaman();

                // Warning jika hampir expired (kurang dari 30 hari)
                if ($riskAssessment->isHampirExpired()) {
                    $pesanBatasWaktu = '⚠️ Batas waktu untuk mengajukan peminjaman akan berakhir dalam '
                        . $sisaWaktu . '. Segera ajukan peminjaman jika diperlukan!';
                }
            }
        }
        else {
            $pesanBatasWaktu = 'Anda belum memiliki Risk Assessment yang disetujui untuk laboratorium ini. '
                . 'Silakan buat Risk Assessment terlebih dahulu sebelum mengajukan peminjaman alat.';
        }

        $peminjaman = PeminjamanAlat::with('alatLab', 'daftarLab')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        // Get RA IDs that have active bebas lab (for cancel confirmation)
        // Format: {risk_assessment_id: bebas_lab_request_id}
        $bebasLabByRa = (object)BebasLabRequest::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('id', 'risk_assessment_id')
            ->toArray();

        return view('mahasiswa.pinjam-alat', compact(
            'lab',
            'user',
            'labs',
            'peminjaman',
            'riskAssessments',
            'riskAssessment',
            'masihBerlaku',
            'pesanBatasWaktu',
            'sisaWaktu',
            'sudahExpired',
            'bebasLabByRa'
        ));
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
    //     $lab = DaftarLab::findOrFail($labId);

    //     // Cek ketersediaan alat
    //     if ($alat->jumlah_tersedia <= 0) {
    //         return back()->with('error', 'Alat tidak tersedia!');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Simpan peminjaman alat
    //         PeminjamanAlat::create([
    //             'user_nama' => $user->Nama,
    //             'alat_lab_id' => $request->alat_lab_id,
    //             'tanggal_pinjam' => $request->tanggal_pinjam,
    //             'tanggal_kembali' => $request->tanggal_kembali,
    //             'status' => 'menunggu',
    //         ]);

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Peminjaman Alat',
    //             'keterangan' => "Mengajukan peminjaman {$alat->nama_alat}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
    //             ->with('success', 'Peminjaman alat berhasil diajukan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    //     }
    // }

    public function storePeminjamanAlat(Request $request, $lab_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // VALIDASI 1: Validasi input form (termasuk risk_assessment_id)
        $request->validate([
            'daftar_lab_id' => 'required|exists:daftar_labs,id',
            'risk_assessment_id' => 'required|exists:risk_assessments,id',
            'alat_lab_id' => 'required|exists:alat_labs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ], [
            'daftar_lab_id.required' => 'Laboratorium harus dipilih',
            'daftar_lab_id.exists' => 'Laboratorium tidak valid',
            'risk_assessment_id.required' => 'Risk Assessment harus dipilih',
            'risk_assessment_id.exists' => 'Risk Assessment tidak valid',
            'alat_lab_id.required' => 'Alat harus dipilih',
            'alat_lab_id.exists' => 'Alat tidak valid',
            'jumlah.required' => 'Jumlah alat harus diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal pinjam',
        ]);

        $lab = DaftarLab::findOrFail($request->daftar_lab_id);

        // VALIDASI 2: Cek Risk Assessment sesuai pilihan
        $riskAssessment = RiskAssessment::where('id', $request->risk_assessment_id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'disetujui_final'])
            ->first();

        if (!$riskAssessment) {
            return back()->with('error', 'Risk Assessment tidak valid atau tidak disetujui.');
        }

        // VALIDASI 2B: Cek apakah RA diajukan untuk grup stok yang sama (Lantai + Jenis Lab)
        if ((int)$riskAssessment->daftarLab->stock_group_id !== (int)$lab->stock_group_id) {
            return back()->with('error', 'Risk Assessment tersebut diajukan untuk Lab ' . $riskAssessment->daftarLab->floor . '.');
        }

        // VALIDASI 3: Cek batas waktu PENGAJUAN peminjaman
        if (!$riskAssessment->isMasihBerlaku()) {
            return back()->with('error',
                'Maaf, batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir ('
                . $riskAssessment->getBatasWaktuPeminjamanFormatted()
                . '). Silakan hubungi Kaprodi untuk perpanjangan batas waktu.'
            );
        }

        $alat = AlatLab::findOrFail($request->alat_lab_id);

        if (!$lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        if ($lab->lab_type !== 'penelitian') {
            return back()->with('error', 'Mahasiswa hanya dapat meminjam alat dari lab penelitian.');
        }

        if ((int)$alat->stock_group_id !== (int)$lab->stock_group_id) {
            return back()->with('error', 'Alat tidak valid untuk grup stok laboratorium ini.');
        }

        // VALIDASI 4: Cek ketersediaan stok
        if ($alat->jumlah_tersedia < $request->jumlah) {
            return back()->with('error',
                'Maaf, stok alat "' . $alat->nama_alat . '" tidak mencukupi! Tersedia: ' . $alat->jumlah_tersedia . ' unit, diminta: ' . $request->jumlah . ' unit.'
            );
        }

        DB::beginTransaction();
        try {
            // Nonaktifkan & batalkan bebas lab yang masih aktif untuk RA yang sama
            $activeBebasLab = \App\Models\BebasLabRequest::where('user_id', $user->id)
                ->where('risk_assessment_id', $riskAssessment->id)
                ->where('is_active', true)
                ->first();

            if ($activeBebasLab) {
                $activeBebasLab->update([
                    'is_active' => false,
                    'status' => 'dibatalkan',
                ]);
            }

            // Simpan peminjaman
            $peminjaman = PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'alat_lab_id' => $request->alat_lab_id,
                'daftar_lab_id' => $request->daftar_lab_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
                'risk_assessment_id' => $riskAssessment->id,
            ]);

            // Catat ke aktivitas mahasiswa
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $request->daftar_lab_id,
                'jenis_aktivitas' => 'Peminjaman Alat',
                'keterangan' => 'Mengajukan peminjaman: ' . $alat->nama_alat
                . ' (Pinjam: ' . Carbon::parse($request->tanggal_pinjam)->format('d M Y') . ')',
                'waktu' => now(),
            ]);

            // ✅ KIRIM EMAIL KE LABORAN
            // Cari laboran untuk lab ini (mendukung multi-role)
            $laborans = DaftarUser::withLaboranRole()
                ->whereHas('laborans', function ($q) use ($lab) {
                $q->where('Laboratorium', $lab->Nama_Laboratorium);
            })
                ->get();

            foreach ($laborans as $laboran) {
                if ($laboran->Email) {
                    try {
                        Mail::to($laboran->Email)->send(
                            new PeminjamanAlatMail($peminjaman->load('alatLab.daftarLab'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman alat berhasil dikirim ke laboran: ' . $laboran->Email);
                    }
                    catch (\Exception $e) {
                        Log::error('Gagal mengirim email ke laboran: ' . $e->getMessage());
                    // Tetap lanjut meskipun email gagal
                    }
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.aktivitas', $request->daftar_lab_id)
                ->with('success',
                'Peminjaman alat "' . $alat->nama_alat . '" berhasil diajukan! '
                . 'Email notifikasi telah dikirim ke laboran. Menunggu persetujuan.'
            );

        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error',
                'Gagal mengajukan peminjaman: ' . $e->getMessage()
            );
        }
    }

    /**
     * Form Pengajuan Penelitian
     */
    // public function formPengajuanPenelitian($labId)
    // {
    //     $lab = DaftarLab::findOrFail($labId);
    //     $user = Auth::user();

    //     $penelitians = PengajuanPenelitian::with('daftarLab')
    // ->where('user_nama', $user->Nama)
    // ->latest()
    // ->get();

    //     return view('mahasiswa.pengajuan-penelitian', compact('lab', 'user'));
    // }

    // /**
    //  * Simpan Pengajuan Penelitian
    //  */
    // public function storePengajuanPenelitian(Request $request, $labId)
    // {
    //     $request->validate([
    //         'judul_penelitian' => 'required|string|max:255',
    //         'deskripsi' => 'required|string',
    //         'tanggal_mulai' => 'required|date|after_or_equal:today',
    //         'tanggal_selesai' => 'required|date|after:tanggal_mulai',
    //         'dosen_pembimbing' => 'required|string|max:255',
    //     ]);

    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     DB::beginTransaction();
    //     try {
    //         // Simpan pengajuan penelitian
    //         PengajuanPenelitian::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'judul_penelitian' => $request->judul_penelitian,
    //             'deskripsi' => $request->deskripsi,
    //             'tanggal_mulai' => $request->tanggal_mulai,
    //             'tanggal_selesai' => $request->tanggal_selesai,
    //             'dosen_pembimbing' => $request->dosen_pembimbing,
    //             'status' => 'menunggu',
    //         ]);

    //         // Catat aktivitas
    //         AktivitasMahasiswa::create([
    //             'user_nama' => $user->Nama,
    //             'daftar_lab_id' => $labId,
    //             'jenis_aktivitas' => 'Pengajuan Penelitian',
    //             'keterangan' => "Mengajukan penelitian: {$request->judul_penelitian}",
    //         ]);

    //         DB::commit();
    //         return redirect()->route('mahasiswa.aktivitas', $labId)
    //             ->with('success', 'Pengajuan penelitian berhasil dikirim!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal mengajukan penelitian: ' . $e->getMessage());
    //     }
    // }

    /**
     * Aktivitas Mahasiswa
     */
    // public function aktivitas($labId)
    // {
    //     $user = Auth::user();
    //     $lab = DaftarLab::findOrFail($labId);

    //     // Ambil semua aktivitas mahasiswa di lab ini
    //     $aktivitas = AktivitasMahasiswa::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('waktu', 'desc')
    //         ->get();

    //     // Ambil status peminjaman
    //     $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
    //         ->with('alatLab')
    //         ->whereHas('alatLab', function($query) use ($labId) {
    //             $query->where('daftar_lab_id', $labId);
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $pengajuanPenelitian = PengajuanPenelitian::where('user_nama', $user->Nama)
    //         ->where('daftar_lab_id', $labId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('mahasiswa.aktivitas', compact(
    //         'lab',
    //         'user',
    //         'aktivitas',
    //         'peminjamanRuangan',
    //         'peminjamanAlat',
    //         'pengajuanPenelitian'
    //     ));
    // }

    public function aktivitas($labId)
    {
        $user = Auth::user();
        $labs = DaftarLab::all();
        $lab = DaftarLab::find($labId); // Jangan pakai findOrFail

        if (!$lab) {
            // Jika lab tidak ditemukan, tampilkan view dengan data kosong dan pesan user-friendly
            return view('mahasiswa.aktivitas', [
                'lab' => null,
                'labs' => $labs,
                'user' => $user,
                'aktivitas' => collect(),
                'peminjamanRuangan' => collect(),
                'peminjamanAlat' => collect(),
                'riskAssessments' => collect(),
                'pesan' => 'Belum ada laboratorium yang tersedia atau lab tidak ditemukan.',
            ]);
        }

        // Ambil semua aktivitas mahasiswa di lab ini
        $aktivitas = AktivitasMahasiswa::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $labId)
            ->orderBy('waktu', 'desc')
            ->get();

        // Ambil status peminjaman
        $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $labId)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
            ->with('alatLab', 'daftarLab')
            ->whereHas('alatLab', function ($query) use ($lab) {
            $query->where('stock_group_id', $lab->stock_group_id)
                ->where(function ($q) use ($lab) {
                $q->whereNull('daftar_lab_id')
                    ->orWhere('daftar_lab_id', $lab->id);
            }
            );
        })
            ->orderBy('created_at', 'desc')
            ->get();

        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->whereHas('daftarLab', function ($query) use ($lab) {
            $query->where('stock_group_id', $lab->stock_group_id)
                ->where(function ($q) use ($lab) {
                $q->whereNull('daftar_lab_id')
                    ->orWhere('daftar_lab_id', $lab->id);
            }
            );
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.aktivitas', compact(
            'lab',
            'labs',
            'user',
            'aktivitas',
            'peminjamanRuangan',
            'peminjamanAlat',
            'riskAssessments'
        ));
    }

    /**
     * Daftar Pengumuman
     */
    public function pengumuman()
    {
        $user = Auth::user();
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pengumuman', compact('pengumuman', 'user'));
    }

    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        return view('mahasiswa.profil', compact('user', 'labs'));
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

            DB::commit();

            return back()->with('success', 'Profil berhasil diupdate!');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
        }
    }
}
