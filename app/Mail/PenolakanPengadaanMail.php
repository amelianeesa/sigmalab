<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PenolakanPengadaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengadaan;
    public $pesanPenolakan;

    public function __construct($pengadaan, $pesanPenolakan)
    {
        $this->pengadaan = $pengadaan;
        $this->pesanPenolakan = $pesanPenolakan;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Penolakan Pengadaan Barang')
                    ->view('emails.penolakan-pengadaan'); // Pastikan buat view blade-nya jika ingin pakai HTML
    }
}