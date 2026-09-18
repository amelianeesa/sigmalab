<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KeterlambatanPengadaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengadaan;
    public $formatHariBerjalan;

    public function __construct($pengadaan, $formatHariBerjalan)
    {
        $this->pengadaan = $pengadaan;
        $this->formatHariBerjalan = $formatHariBerjalan;
    }

    public function build()
    {
        return $this->subject('[URGENT] Peringatan Keterlambatan: ' . ($this->pengadaan->barang->nama_barang ?? 'Barang'))
                    ->view('emails.keterlambatan');
    }
}