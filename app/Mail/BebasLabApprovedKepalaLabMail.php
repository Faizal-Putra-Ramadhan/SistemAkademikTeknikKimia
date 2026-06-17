<?php

namespace App\Mail;

use App\Models\BebasLabRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BebasLabApprovedKepalaLabMail extends Mailable
{
    use Queueable, SerializesModels;

    public BebasLabRequest $bebasLabRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(BebasLabRequest $bebasLabRequest)
    {
        $this->bebasLabRequest = $bebasLabRequest;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('[LIMS] Notifikasi Bebas Lab Disetujui - '.$this->bebasLabRequest->user_nama)
            ->view('emails.bebas-lab-approved-kepala-lab')
            ->with([
                'bebasLabRequest' => $this->bebasLabRequest,
            ]);
    }
}
