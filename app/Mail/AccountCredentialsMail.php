<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;

    public $email;

    public $password;

    public $userId;

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $email, $password, $userId)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;
        $this->userId = $userId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->email,
            subject: 'Informasi Akun - Sistem RegLab UAD',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-credentials',
            with: [
                'nama' => $this->nama,
                'email' => $this->email,
                'password' => $this->password,
                'userId' => $this->userId,
                'loginUrl' => url('/login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
