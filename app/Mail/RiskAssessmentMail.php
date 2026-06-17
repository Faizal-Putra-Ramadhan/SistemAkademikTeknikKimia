<?php

namespace App\Mail;

use App\Models\RiskAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RiskAssessmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ra;

    public $type;

    public $customMessage;

    /**
     * @param  $ra  (Object RiskAssessment)
     * @param  $type  (String: 'ke_dosen', 'dosen_setuju', 'ke_so', 'jadwal_so', 'hasil_kalab', 'ke_kaprodi', 'hasil_kaprodi')
     */
    public function __construct(RiskAssessment $ra, $type, $customMessage = '')
    {
        $this->ra = $ra;
        $this->type = $type;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        $subjects = [
            'ke_dosen' => 'Pengajuan Risk Assessment Baru - '.$this->ra->user->Nama,
            'dosen_setuju' => 'Update: Risk Assessment Disetujui Dosen Pembimbing',
            'ke_so' => 'Permintaan Review Risk Assessment - Safety Officer',
            'jadwal_so' => 'Jadwal Wawancara Risk Assessment',
            'jadwal_options_so' => 'Pilih Jadwal Wawancara Risk Assessment - '.count($this->ra->jadwal_wawancara_options ?? []).' Opsi Tersedia',
            'jadwal_dipilih_mahasiswa' => 'Mahasiswa Telah Memilih Jadwal Wawancara - '.$this->ra->user->Nama,
            'hasil_kalab' => 'Status Risk Assessment - Kepala Lab',
            'ke_kaprodi' => 'Pengajuan Risk Assessment Mahasiswa - Kaprodi',
            'hasil_kaprodi' => 'Keputusan Final Risk Assessment - Kaprodi',
            'ajukan_perpanjangan' => 'Permohonan Perpanjangan RA Baru - '.$this->ra->user->Nama,
            'batal_perpanjangan' => 'Pembatalan Pengajuan Perpanjangan RA - '.$this->ra->user->Nama,
            'hasil_perpanjangan' => 'Update Status Perpanjangan Risk Assessment',
            'ke_kepala_lab' => 'Persetujuan Risk Assessment: Perlu Validasi Kepala Lab',
            'perpanjangan_deadline' => 'Pengingat Perpanjangan Peminjaman Alat - Risk Assessment',
        ];

        return $this->subject($subjects[$this->type] ?? 'Notifikasi Risk Assessment')
            ->view('emails.risk_assessment_template');
    }
}
