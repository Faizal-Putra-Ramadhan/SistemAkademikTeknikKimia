<?php

namespace App\Mail;

use App\Models\PeminjamanAlat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeminjamanAlatMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjamanAlat;

    public $type;

    public $catatan;

    /**
     * Create a new message instance.
     */
    public function __construct(PeminjamanAlat $peminjamanAlat, $type, $catatan = null)
    {
        $this->peminjamanAlat = $peminjamanAlat;
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
            ->view('emails.peminjaman-alat')
            ->with([
                'peminjamanAlat' => $this->peminjamanAlat,
                'type' => $this->type,
                'catatan' => $this->catatan,
            ]);
    }

    /**
     * Get email subject based on type
     */
    private function getSubject()
    {
        switch ($this->type) {
            case 'pengajuan_ke_laboran':
                return '[LIMS] Pengajuan Peminjaman Alat Baru - '.$this->peminjamanAlat->alatLab->nama_alat;

            case 'hasil_laboran':
                $status = $this->peminjamanAlat->status === 'disetujui' ? 'Disetujui' : 'Ditolak';

                return '[LIMS] Peminjaman Alat '.$status.' - '.$this->peminjamanAlat->alatLab->nama_alat;

            default:
                return '[LIMS] Notifikasi Peminjaman Alat';
        }
    }
}
