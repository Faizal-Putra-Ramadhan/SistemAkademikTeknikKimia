<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $resetUrl = route('password.reset', ['token' => $this->data['token'], 'email' => $this->data['email']]);
        
        return $this->subject('Reset Password - LAB TEKIM UAD')
                    ->view('emails.reset-password')
                    ->with([
                        'resetUrl' => $resetUrl,
                        'name' => $this->data['name'],
                        'email' => $this->data['email']
                    ]);
    }
}