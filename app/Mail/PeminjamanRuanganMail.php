<?php

namespace App\Mail;

use App\Models\PeminjamanRuangan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeminjamanRuanganMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjamanRuangan;

    public $type;

    public $catatan;

    /**
     * Create a new message instance.
     */
    public function __construct(PeminjamanRuangan $peminjamanRuangan, $type, $catatan = null)
    {
        $this->peminjamanRuangan = $peminjamanRuangan;
        $this->type = $type;
        $this->catatan = $catatan;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->getSubject();

        return $this->subject($subject)
            ->view('emails.peminjaman-ruangan')
            ->with([
                'peminjamanRuangan' => $this->peminjamanRuangan,
                'type' => $this->type,
                'catatan' => $this->catatan,
            ]);
    }

    /**
     * Get email subject based on type
     */
    private function getSubject()
    {
        $labName = $this->peminjamanRuangan->daftarLab->Nama_Laboratorium;

        switch ($this->type) {
            case 'pengajuan_ke_laboran':
                return '[LIMS] Pengajuan Peminjaman Ruangan - '.$labName;

            case 'hasil_laboran':
                $status = $this->peminjamanRuangan->persetujuan_laboran ? 'Disetujui Laboran' : 'Ditolak';

                return '[LIMS] Peminjaman Ruangan '.$status.' - '.$labName;

            case 'pengajuan_ke_kepala_lab':
                return '[LIMS] Perlu Persetujuan Kepala Lab - Peminjaman Ruangan '.$labName;

            case 'notifikasi_kaprodi':
                return '[LIMS] Notifikasi Kaprodi - Peminjaman Ruangan '.$labName;

            case 'hasil_kaprodi':
                $status = $this->peminjamanRuangan->persetujuan_kaprodi ? 'Disetujui' : 'Ditolak';

                return '[LIMS] Peminjaman Ruangan '.$status.' - '.$labName;

            default:
                return '[LIMS] Notifikasi Peminjaman Ruangan';
        }
    }
}
