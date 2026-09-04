<?php

namespace App\Http\Controllers\PenelitiEksternal;

use App\Http\Controllers\Controller;
use App\Mail\PeminjamanAlatMail;
use App\Models\AktivitasMahasiswa;
use App\Models\AlatLab;
use App\Models\BebasLabApproval;
use App\Models\BebasLabRequest;
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanRuangan;
use App\Models\Pengumuman;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Controller untuk Peneliti Eksternal
 * Menggunakan logika yang sama dengan Mahasiswa tapi untuk role Peneliti Eksternal
 */
class PenelitiEksternalController extends Controller
{
    /**
     * Dashboard Peneliti Eksternal - Menampilkan semua lab
     */
    public function dashboard()
    {
        $labs = DaftarLab::penelitian()->get();
        $user = Auth::user();
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peneliti-eksternal.dashboard', compact('labs', 'user', 'pengumuman'));
    }

    /**
     * Detail Lab - Menampilkan info lab dan alat yang tersedia
     */
    public function detailLab($id)
    {
        $lab = DaftarLab::with('alatLabs')->findOrFail($id);
        $user = Auth::user();

        return view('peneliti-eksternal.detail-lab', compact('lab', 'user'));
    }

    /**
     * Profil Peneliti Eksternal
     */
    public function profil()
    {
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        return view('peneliti-eksternal.profil', compact('user', 'labs'));
    }

    /**
     * Update Profil Peneliti Eksternal
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Phone' => 'required|string|max:20',
            'Email' => 'required|email|max:255|unique:daftar_users,Email,'.$user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'Nama' => $request->Nama,
                'Phone' => $request->Phone,
                'Email' => $request->Email,
            ];

            
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile'), $filename);
                $updateData['foto'] = $filename;

                if ($user->foto && file_exists(public_path('uploads/profile/'.$user->foto))) {
                    unlink(public_path('uploads/profile/'.$user->foto));
                }
            }

            $user->update($updateData);
            DB::commit();

            return redirect()->route('peneliti-eksternal.profil')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('peneliti-eksternal.profil')
                ->with('error', 'Gagal memperbarui profil: '.$e->getMessage());
        }
    }

    /**
     * Form Peminjaman Alat
     */
    public function formPeminjamanAlat($id)
    {
        $labs = DaftarLab::penelitian()->get();
        $lab = DaftarLab::with('alatLabs')->find($id);
        $user = Auth::user();

        if ($lab && ! $lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        
        
        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->get();

        
        foreach ($riskAssessments as $ra) {
            if ($ra instanceof RiskAssessment && ! $ra->id_ra) {
                $ra->generateIdRa();
            }
        }

        
        $validRiskAssessments = $riskAssessments->filter(function ($ra) {
            return $ra->isMasihBerlaku();
        });

        $riskAssessment = $validRiskAssessments->sortByDesc('batas_waktu_peminjaman')->first()
            ?? $riskAssessments->sortByDesc('batas_waktu_peminjaman')->first();

        
        $masihBerlaku = false;
        $pesanBatasWaktu = null;
        $sisaWaktu = null;

        if ($riskAssessment) {
            $masihBerlaku = $validRiskAssessments->isNotEmpty();

            if (! $masihBerlaku) {
                $pesanBatasWaktu = 'Batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir. '
                    .'Terakhir bisa mengajukan: '.$riskAssessment->getBatasWaktuPeminjamanFormatted()
                    .'. Silakan hubungi Kaprodi untuk perpanjangan.';
            } else {
                $sisaWaktu = $riskAssessment->getSisaWaktuPeminjaman();

                
                if ($riskAssessment->isHampirExpired()) {
                    $pesanBatasWaktu = 'Perhatian: Batas waktu pengajuan peminjaman akan berakhir dalam '.$sisaWaktu
                        .'. Segera ajukan peminjaman jika Anda membutuhkan alat.';
                }
            }
        } else {
            $pesanBatasWaktu = 'Anda belum memiliki Risk Assessment yang disetujui. '
                .'Silakan buat Risk Assessment terlebih dahulu sebelum mengajukan peminjaman alat.';
        }

        
        $peminjaman_alats = PeminjamanAlat::with('alatLab', 'riskAssessment', 'daftarLab')
            ->where('user_nama', $user->Nama)
            ->latest()
            ->get();

        return view('peneliti-eksternal.pinjam-alat', compact(
            'lab',
            'labs',
            'user',
            'peminjaman_alats',
            'riskAssessment',
            'riskAssessments',
            'masihBerlaku',
            'pesanBatasWaktu',
            'sisaWaktu'
        ));
    }

    /**
     * Simpan Peminjaman Alat
     */
    public function storePeminjamanAlat(Request $request, $id)
    {
        $user = Auth::user();

        
        $request->validate([
            'risk_assessment_id' => 'required|exists:risk_assessments,id',
            'alat_lab_id' => 'required|exists:alat_labs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ], [
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

        
        $riskAssessment = RiskAssessment::where('id', $request->risk_assessment_id)
            ->where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->first();

        if (! $riskAssessment) {
            return back()->with('error',
                'Risk Assessment tidak valid atau tidak disetujui.'
            );
        }

        
        if (! $riskAssessment->isMasihBerlaku()) {
            return back()->with('error',
                'Maaf, batas waktu untuk PENGAJUAN peminjaman alat sudah berakhir ('
                .$riskAssessment->getBatasWaktuPeminjamanFormatted()
                .'). Silakan hubungi Kaprodi untuk perpanjangan batas waktu.'
            );
        }

        
        if ($riskAssessment->batas_waktu_peminjaman &&
            Carbon::parse($riskAssessment->batas_waktu_peminjaman)->isPast()) {
            return back()->with('error',
                'Maaf, batas waktu untuk peminjaman alat sudah berakhir. '
                .'Deadline peminjaman: '.Carbon::parse($riskAssessment->batas_waktu_peminjaman)->format('d F Y')
                .'. Silakan hubungi Laboran untuk perpanjangan deadline atau ajukan peminjaman baru.'
            );
        }

        $lab = DaftarLab::findOrFail($id);
        $alat = AlatLab::findOrFail($request->alat_lab_id);

        if (! $lab->stock_group_id) {
            return back()->with('error', 'Lab belum memiliki grup stok. Hubungi admin untuk melengkapi data lantai dan jenis lab.');
        }

        if ((int) $alat->stock_group_id !== (int) $lab->stock_group_id) {
            return back()->with('error', 'Alat tidak valid untuk grup stok laboratorium ini.');
        }

        
        if ($alat->jumlah_tersedia <= 0) {
            return back()->with('error',
                'Maaf, alat "'.$alat->nama_alat.'" sedang tidak tersedia (stok habis)!'
            );
        }

        DB::beginTransaction();
        try {
            
            $activeBebasLabs = BebasLabRequest::where('user_id', $user->id)
                ->where('risk_assessment_id', $request->risk_assessment_id)
                ->where('is_active', true)
                ->get();

            foreach ($activeBebasLabs as $bebasLab) {
                $bebasLab->deactivate();
            }

            $peminjaman = PeminjamanAlat::create([
                'user_nama' => $user->Nama,
                'risk_assessment_id' => $request->risk_assessment_id,
                'alat_lab_id' => $request->alat_lab_id,
                'daftar_lab_id' => $id,
                'jumlah' => $request->jumlah ?? 1,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'menunggu',
            ]);

            
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $id,
                'jenis_aktivitas' => 'Peminjaman Alat',
                'keterangan' => 'Mengajukan peminjaman: '.$alat->nama_alat
                    .' (RA: '.$riskAssessment->id_ra.', Pinjam: '.Carbon::parse($request->tanggal_pinjam)->format('d M Y').')',
                'waktu' => now(),
            ]);

            $lab = DaftarLab::findOrFail($id);

            
            $laborans = DaftarUser::withLaboranRole()
                ->whereHas('laborans', function ($q) use ($lab) {
                    $q->where('Laboratorium', $lab->Nama_Laboratorium);
                })
                ->get();

            foreach ($laborans as $laboran) {
                if ($laboran && $laboran->Email) {
                    try {
                        Mail::to($laboran->Email)->send(
                            new PeminjamanAlatMail($peminjaman->load('alatLab.daftarLab', 'riskAssessment'), 'pengajuan_ke_laboran')
                        );
                        Log::info('Email peminjaman alat berhasil dikirim ke laboran: '.$laboran->Email);
                    } catch (\Exception $e) {
                        Log::error('Gagal mengirim email peminjaman alat: '.$e->getMessage());
                    }
                }
            }

            DB::commit();

            return redirect()->route('peneliti-eksternal.aktivitas', $id)
                ->with('success',
                    'Peminjaman alat "'.$alat->nama_alat.'" berhasil diajukan with Risk Assessment '.$riskAssessment->id_ra.'! '
                    .'Email notifikasi telah dikirim ke laboran. Menunggu persetujuan.'
                );
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengajukan peminjaman: '.$e->getMessage());
        }
    }

    /**
     * Ajukan pengembalian alat oleh peneliti eksternal
     */
    public function ajukanPengembalian(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $peminjaman = PeminjamanAlat::findOrFail($id);

        
        if ($peminjaman->user_nama !== $user->Nama) {
            return back()->with('error', 'Anda tidak berhak mengajukan pengembalian untuk peminjaman ini.');
        }

        
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa dikembalikan.');
        }

        
        if ($peminjaman->pengajuan_pengembalian) {
            return back()->with('error', 'Anda sudah mengajukan pengembalian untuk alat ini.');
        }

        $request->validate([
            'keterangan_pengembalian' => 'nullable|string|max:500',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
        ], [
            'kondisi_barang.required' => 'Kondisi barang harus dipilih',
            'kondisi_barang.in' => 'Kondisi barang tidak valid',
        ]);

        DB::beginTransaction();
        try {
            
            $peminjaman->update([
                'pengajuan_pengembalian' => true,
                'tanggal_pengajuan_pengembalian' => now(),
                'keterangan_pengembalian' => $request->keterangan_pengembalian,
                'kondisi_barang' => $request->kondisi_barang,
            ]);

            
            AktivitasMahasiswa::create([
                'user_nama' => $user->Nama,
                'daftar_lab_id' => $peminjaman->alatLab->daftar_lab_id,
                'jenis_aktivitas' => 'Pengembalian Alat',
                'keterangan' => 'Mengajukan pengembalian: '.$peminjaman->alatLab->nama_alat
                    .' (Kondisi: '.ucfirst($request->kondisi_barang).')',
                'waktu' => now(),
            ]);

            DB::commit();

            return back()->with('success',
                'Pengajuan pengembalian alat "'.$peminjaman->alatLab->nama_alat.'" berhasil diajukan! '
                .'Menunggu verifikasi dari laboran.'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error',
                'Gagal mengajukan pengembalian: '.$e->getMessage()
            );
        }
    }

    /**
     * Aktivitas Peneliti Eksternal
     */
    public function aktivitas($id)
    {
        $lab = DaftarLab::find($id);
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        if (! $lab) {
            
            return view('peneliti-eksternal.aktivitas', [
                'lab' => null,
                'user' => $user,
                'labs' => $labs,
                'aktivitas' => collect(),
                'peminjamanRuangan' => collect(),
                'peminjamanAlat' => collect(),
                'riskAssessments' => collect(),
                'labNotFound' => true,
            ]);
        }

        $aktivitas = AktivitasMahasiswa::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $id)
            ->orderBy('waktu', 'desc')
            ->get();

        $peminjamanRuangan = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->where('daftar_lab_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAlat = PeminjamanAlat::where('user_nama', $user->Nama)
            ->with('alatLab', 'riskAssessment', 'daftarLab')
            ->whereHas('alatLab', function ($query) use ($lab) {
                $query->where('stock_group_id', $lab->stock_group_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->whereHas('daftarLab', function ($query) use ($lab) {
                $query->where('stock_group_id', $lab->stock_group_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peneliti-eksternal.aktivitas', compact('lab', 'user', 'labs', 'aktivitas', 'peminjamanRuangan', 'peminjamanAlat', 'riskAssessments'));
    }

    /**
     * Form Bebas Lab
     */
    public function formBebasLab()
    {
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        
        $allBebasLabRaIds = BebasLabRequest::where('user_id', $user->id)
            ->pluck('risk_assessment_id')
            ->unique()
            ->toArray();

        $excludeRaIds = [];
        foreach ($allBebasLabRaIds as $raId) {
            $hasActiveBebas = BebasLabRequest::where('user_id', $user->id)
                ->where('risk_assessment_id', $raId)
                ->where('is_active', true)
                ->exists();

            if ($hasActiveBebas) {
                $excludeRaIds[] = $raId;
            } else {
                $hasActivePeminjaman = PeminjamanAlat::where('user_nama', $user->Nama)
                    ->where('risk_assessment_id', $raId)
                    ->whereIn('status', ['menunggu', 'disetujui'])
                    ->exists()
                || PeminjamanRuangan::where('user_nama', $user->Nama)
                    ->where('risk_assessment_id', $raId)
                    ->whereIn('status', ['menunggu_laboran', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui'])
                    ->exists();

                if (! $hasActivePeminjaman) {
                    $excludeRaIds[] = $raId;
                }
            }
        }

        
        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereNotIn('id', $excludeRaIds)
            ->orderBy('created_at', 'desc')
            ->get();

        
        $bebasLabRequests = BebasLabRequest::with(['approvals.lab', 'riskAssessment.daftarLab'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        
        foreach ($bebasLabRequests as $key => $blr) {
            if ($blr->status === 'disetujui' && $blr->hasPeminjamanAktif()) {
                $blr->deactivate();
                $bebasLabRequests->forget($key);
            }
        }
        $bebasLabRequests = $bebasLabRequests->values(); 

        $peminjamanAlats = PeminjamanAlat::with('alatLab.daftarLab')
            ->where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peneliti-eksternal.bebas-lab', compact(
            'user',
            'labs',
            'bebasLabRequests',
            'peminjamanAlats',
            'riskAssessments'
        ));
    }

    /**
     * Store Bebas Lab
     */
    public function storeBebasLab(Request $request)
    {
        $user = Auth::user();

        
        $request->validate([
            'risk_assessment_id' => 'required|integer|exists:risk_assessments,id',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($request->risk_assessment_id);

        
        if ($riskAssessment->user_id !== $user->id || $riskAssessment->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Risk Assessment tidak valid atau belum disetujui.');
        }

        
        $existingActive = BebasLabRequest::where('user_id', $user->id)
            ->where('risk_assessment_id', $request->risk_assessment_id)
            ->where('is_active', true)
            ->first();

        if ($existingActive) {
            return redirect()->back()->with('error', 'Risk Assessment ini sudah memiliki pengajuan Bebas Lab yang masih aktif.');
        }

        
        $previousCount = BebasLabRequest::where('user_id', $user->id)
            ->where('risk_assessment_id', $request->risk_assessment_id)
            ->count();
        $periode = $previousCount + 1;

        $bebasLabRequest = BebasLabRequest::create([
            'user_id' => $user->id,
            'user_nama' => $user->Nama,
            'risk_assessment_id' => $request->risk_assessment_id,
            'status' => 'menunggu',
            'is_active' => true,
            'periode' => $periode,
        ]);

        $laborans = DaftarLaboranLaboratorium::with(['laboratoriums', 'daftarLab'])->get();

        
        foreach ($laborans as $laboran) {
            
            if ($laboran->laboratoriums && $laboran->laboratoriums->isNotEmpty()) {
                foreach ($laboran->laboratoriums as $lab) {
                    BebasLabApproval::create([
                        'bebas_lab_request_id' => $bebasLabRequest->id,
                        'daftar_lab_id' => $lab->id,
                        'laboran_user_id' => $laboran->UserID,
                        'laboran_nama' => $laboran->Nama_Laboran,
                        'status' => 'menunggu',
                    ]);
                }
            }
            
            elseif ($laboran->daftarLab) {
                BebasLabApproval::create([
                    'bebas_lab_request_id' => $bebasLabRequest->id,
                    'daftar_lab_id' => $laboran->daftarLab->id,
                    'laboran_user_id' => $laboran->UserID,
                    'laboran_nama' => $laboran->Nama_Laboran,
                    'status' => 'menunggu',
                ]);
            }
        }

        foreach ($laborans as $laboran) {
            try {
                if (! empty($laboran->Email)) {
                    Mail::to($laboran->Email)->send(new \App\Mail\BebasLabMail($bebasLabRequest));
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email bebas lab: '.$e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pengajuan Bebas Lab berhasil dikirim. Menunggu persetujuan laboran.');
    }

    public function downloadBebasLab($id)
    {
        $user = Auth::user();
        $bebasLabRequest = BebasLabRequest::with(['approvals.lab'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        
        if (! $bebasLabRequest->isFullyApproved()) {
            return redirect()->back()->with('error', 'Pengajuan Bebas Lab belum disetujui oleh semua laboran.');
        }

        
        if (! $bebasLabRequest->is_active || ! $bebasLabRequest->isMasihBerlaku()) {
            return redirect()->back()->with('error', 'Bebas Lab Anda sudah tidak aktif atau masa berlakunya habis. Silakan ajukan ulang.');
        }

        
        if ($bebasLabRequest->hasPeminjamanAktif()) {
            return redirect()->back()->with('error', 'Anda memiliki peminjaman aktif. Bebas Lab harus diajukan ulang setelah peminjaman selesai.');
        }

        $peminjamanAlats = PeminjamanAlat::with('alatLab.daftarLab')
            ->where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        
        $templatePath = storage_path('app/templates/bebas_lab.docx');

        App::setLocale('id');
        Carbon::setLocale('id');
        Carbon::setLocale('id');
        
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $current_time = Carbon::now('Asia/Jakarta')->translatedFormat(('d F Y'));
        $formatDate = fn ($value) => $value ? Carbon::parse($value)->translatedFormat('d F Y') : '-';

        
        $phpWord->setValues([
            'NAMA_MAHASISWA' => $bebasLabRequest->user_nama,
            'NIM' => $bebasLabRequest->user?->Nomor_Identitas ?? '-',
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

                Log::info("Setting row {$rowNumber}");

                
                $phpWord->setValue('NOMOR#'.$rowNumber, (string) $rowNumber);

                
                $phpWord->setValue('LAB_NAME#'.$rowNumber, $approval->lab->Nama_Laboratorium ?? '-');

                
                $phpWord->setValue('LABORAN_NAME#'.$rowNumber, $approval->laboran_nama ?? '-');

                
                if ($approval->status === 'disetujui') {
                    $tanggal = $approval->approved_at ? $approval->approved_at->format('d/m/Y') : '';
                    $persetujuan = ' Disetujui'.($tanggal ? " ({$tanggal})" : '');
                } else {
                    $persetujuan = 'Menunggu';
                }
                $phpWord->setValue('PERSETUJUAN#'.$rowNumber, $persetujuan);

                Log::info("Row {$rowNumber} set: NOMOR={$rowNumber}, LAB={$approval->lab->Nama_Laboratorium}");
            }
        }

        
        $kepalaLabs = DaftarUser::where(function ($query) {
            $query->where('Role_User', 'Kepala Laboratorium')
                ->orWhereHas('roles', fn ($q) => $q->where('name', 'Kepala Laboratorium'));
        })->get()->unique('id');

        Log::info('=== DEBUG KEPALA LAB ===');
        Log::info('Jumlah Kepala Lab ditemukan: '.$kepalaLabs->count());

        if ($kepalaLabs->count() === 0) {
            
            Log::warning('Tidak ada Kepala Laboratorium, coba cek semua role:');
            $allRoles = DaftarUser::select('Role_User')->distinct()->get();
            Log::info('Semua Role yang ada: '.json_encode($allRoles->pluck('Role')));
        }

        
        for ($i = 1; $i <= 2; $i++) {
            if ($kepalaLabs->count() >= $i) {
                $kepalaLab = $kepalaLabs[$i - 1];
                $nama = $kepalaLab->Nama ?? '-';
                $noIdentitas = $kepalaLab->Nomor_Identitas ?? '-';
                $persetujuan_kepala_lab = 'Disetujui';

                $phpWord->setValue('KEPALA_LAB_'.$i, $nama);
                $phpWord->setValue('NO_IDENTITAS_'.$i, $noIdentitas);
                $phpWord->setValue('PERSETUJUAN_KEPALA_LAB_'.$i, $persetujuan_kepala_lab);

                Log::info("KEPALA_LAB_{$i} = {$nama}");
                Log::info("NO_IDENTITAS_{$i} = {$noIdentitas}");
            } else {
                
                $phpWord->setValue('KEPALA_LAB_'.$i, '-');
                $phpWord->setValue('NO_IDENTITAS_'.$i, '-');
                $phpWord->setValue('PERSETUJUAN_KEPALA_LAB_'.$i, '-');
                Log::warning("KEPALA_LAB_{$i} tidak ada data, diisi dengan '-'");
            }
        }

        
        $fileName = 'Bebas_Lab_'.$bebasLabRequest->id.'.docx';

        
        $savePath = storage_path('app/public/'.$fileName);

        $phpWord->saveAs($savePath);

        
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    public function pengumuman()
    {
        $pengumuman = Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peneliti-eksternal.pengumuman', compact('pengumuman'));
    }
}
