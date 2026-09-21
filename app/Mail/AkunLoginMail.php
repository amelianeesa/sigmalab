<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AkunLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaPersonil,
        public string $username,
        public string $email,
        public string $passwordSementara,
    ) {}

    public function build()
    {
        return $this->subject('SIGMA-LAB — Akun Login Anda')
            ->view('emails.akun-login');
    }
}