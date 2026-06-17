<?php

namespace App\Mail;

use App\Models\BebasLabRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BebasLabMail extends Mailable
{
    use Queueable, SerializesModels;

    public BebasLabRequest $request;

    public bool $isResubmit;

    public function __construct(BebasLabRequest $request, bool $isResubmit = false)
    {
        $this->request = $request;
        $this->isResubmit = $isResubmit;
    }

    public function build()
    {
        $subject = $this->isResubmit
            ? '[LIMS] Pengajuan Ulang Bebas Lab (Periode '.$this->request->periode.') - '.$this->request->user_nama
            : '[LIMS] Pengajuan Bebas Lab - '.$this->request->user_nama;

        return $this->subject($subject)
            ->view('emails.bebas-lab-request')
            ->with([
                'request' => $this->request,
                'isResubmit' => $this->isResubmit,
            ]);
    }
}
