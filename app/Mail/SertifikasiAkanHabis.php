<?php

namespace App\Mail;

use App\Models\KompetensiPersonil;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SertifikasiAkanHabis extends Mailable
{
    use Queueable, SerializesModels;

    public KompetensiPersonil $kompetensi;

    public function __construct(KompetensiPersonil $kompetensi)
    {
        $this->kompetensi = $kompetensi;
    }

    public function build()
    {
        return $this->subject('Peringatan: Sertifikasi/Pelatihan Akan Habis - ' . $this->kompetensi->jenis_sertifikasi)
            ->view('emails.sertifikasi-habis')
            ->with([
                'kompetensi' => $this->kompetensi,
            ]);
    }
}
