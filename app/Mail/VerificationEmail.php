<?php
// app/Mail/VerificationEmail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $verificationUrl)
    {
        $this->nama = $nama;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verifikasi Email - Sistem RegLab UAD')
                    ->view('emails.verification');
    }
}