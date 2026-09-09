<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KalibrasiAkanHabis extends Mailable
{
    use Queueable, SerializesModels;

    public $kalibrasi;

    public function __construct($kalibrasi)
    {
        $this->kalibrasi = $kalibrasi;
    }

    public function build()
    {
        return $this->subject('Peringatan: Masa Kalibrasi Alat Akan Habis')
                    ->view('emails.kalibrasi-habis');
    }
}