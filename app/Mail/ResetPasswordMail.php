<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $user,
        public string $token,
    ) {}

    public function build()
    {
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $this->user->email]);

        return $this->subject('SIGMA-LAB — Reset Password Anda')
            ->view('emails.reset-password')
            ->with([
                'resetUrl' => $resetUrl,
                'namaUser' => $this->user->personil->nama ?? $this->user->username,
                'user' => $this->user,
            ]);
    }
}