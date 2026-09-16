<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaporanKerusakanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $perbaikan;

    public function __construct($perbaikan)
    {
        $this->perbaikan = $perbaikan;
    }

    public function build()
    {
        return $this->subject('Peringatan: Laporan Kerusakan Alat Laboratorium')
                    ->view('emails.laporan-kerusakan');
    }
}