<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanPengadaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengadaan;

    public function __construct($pengadaan)
    {
        $this->pengadaan = $pengadaan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Persetujuan Diperlukan: Pengajuan Pengadaan Barang - ' . ($this->pengadaan->barang->nama_barang ?? '-'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan-pengadaan',
            with: [
                'pengadaan' => $this->pengadaan,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}