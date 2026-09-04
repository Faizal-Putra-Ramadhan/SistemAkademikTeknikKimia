<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Mail\BebasLabMail;
use App\Models\BebasLabApproval;
use App\Models\BebasLabRequest;
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanRuangan;
use App\Models\RiskAssessment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BebasLabController extends Controller
{
    public function index()
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
                    ->whereIn('status', ['menunggu', 'disetujui', 'disetujui_final'])
                    ->exists()
                    || PeminjamanRuangan::where('user_nama', $user->Nama)
                        ->where('risk_assessment_id', $raId)
                        ->whereIn('status', ['menunggu_laboran', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui', 'disetujui_final'])
                        ->exists();

                if (! $hasActivePeminjaman) {
                    $excludeRaIds[] = $raId;
                }
            }
        }

        
        $riskAssessments = RiskAssessment::where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'disetujui_final'])
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

        $historyRequests = BebasLabRequest::with(['riskAssessment.daftarLab', 'approvals.lab'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanAlats = PeminjamanAlat::with('alatLab.daftarLab')
            ->where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanRuangans = PeminjamanRuangan::where('user_nama', $user->Nama)
            ->orderBy('created_at', 'desc')
            ->get();

        $alatByRa = $peminjamanAlats->groupBy('risk_assessment_id');
        $ruanganByRa = $peminjamanRuangans->groupBy('risk_assessment_id');

        return view('mahasiswa.bebas-lab', compact(
            'user',
            'labs',
            'bebasLabRequests',
            'peminjamanAlats',
            'riskAssessments',
            'historyRequests',
            'alatByRa',
            'ruanganByRa'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        
        $request->validate([
            'risk_assessment_id' => 'required|integer|exists:risk_assessments,id',
        ]);

        $riskAssessment = RiskAssessment::findOrFail($request->risk_assessment_id);

        
        if ($riskAssessment->user_id !== $user->id || ! in_array($riskAssessment->status, ['disetujui', 'disetujui_final'])) {
            return redirect()->back()->with('error', 'Risk Assessment tidak valid atau belum disetujui.');
        }

        
        $existingActive = BebasLabRequest::where('user_id', $user->id)
            ->where('risk_assessment_id', $request->risk_assessment_id)
            ->where('is_active', true)
            ->first();

        if ($existingActive) {
            return redirect()->back()->with('error', 'Risk Assessment dengan kode '.($riskAssessment->id_ra ?? 'RA-'.$riskAssessment->id).' sudah memiliki pengajuan Bebas Lab yang masih aktif.');
        }

        
        $previousCount = BebasLabRequest::where('user_id', $user->id)
            ->where('risk_assessment_id', $request->risk_assessment_id)
            ->count();
        $periode = $previousCount + 1;
        $isResubmit = $periode > 1;

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
                    Mail::to($laboran->Email)->send(new BebasLabMail($bebasLabRequest, $isResubmit));
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email bebas lab ke laboran: '.$e->getMessage());
            }
        }

        $successMessage = $isResubmit
            ? 'Pengajuan ulang Bebas Lab (Periode '.$periode.') berhasil dikirim. Menunggu persetujuan laboran.'
            : 'Pengajuan Bebas Lab berhasil dikirim. Menunggu persetujuan laboran.';

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Batalkan bebas lab agar RA bisa digunakan untuk peminjaman alat
     */
    public function cancelBebasLab($id)
    {
        $user = Auth::user();

        $bebasLabRequest = BebasLabRequest::where('user_id', $user->id)
            ->where('is_active', true)
            ->findOrFail($id);

        $bebasLabRequest->update([
            'is_active' => false,
            'status' => 'dibatalkan',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bebas Lab berhasil dibatalkan. Anda sekarang dapat menggunakan Risk Assessment ini untuk peminjaman alat.',
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $labs = DaftarLab::penelitian()->get();

        $bebasLabRequest = BebasLabRequest::with(['approvals.lab', 'riskAssessment.daftarLab'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $peminjamanAlats = PeminjamanAlat::with('alatLab.daftarLab')
            ->where('user_nama', $user->Nama)
            ->where('risk_assessment_id', $bebasLabRequest->risk_assessment_id)
            ->where('created_at', '<=', $bebasLabRequest->created_at)
            ->orderBy('created_at', 'desc')
            ->get();

        $peminjamanRuangans = PeminjamanRuangan::with('daftarLab')
            ->where('user_nama', $user->Nama)
            ->where('risk_assessment_id', $bebasLabRequest->risk_assessment_id)
            ->where('created_at', '<=', $bebasLabRequest->created_at)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.bebas-lab-detail', compact(
            'user',
            'labs',
            'bebasLabRequest',
            'peminjamanAlats',
            'peminjamanRuangans'
        ));
    }

    public function download($id)
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
                $noIdentitas = $kepalaLab->nomor_identitas ?? '-';
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
}
