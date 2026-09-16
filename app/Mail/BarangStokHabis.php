<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BarangStokHabis extends Mailable
{
    use Queueable, SerializesModels;

    public $barang;

    // Ditambahkan: dulu kosong, sekarang diisi ringkasan alasan kritis
    // (mis. "Stok Menipis/Habis (Sisa: X, Min: Y)" atau "Mendekati Kadaluarsa (Exp: ...)")
    // yang dikirim dari CekStokBarangHabis command. Nullable supaya tempat lain yang
    // masih manggil `new BarangStokHabis($barang)` tanpa argumen kedua tetap jalan.
    public $statusPesan;

    public function __construct($barang, $statusPesan = null)
    {
        $this->barang = $barang;
        $this->statusPesan = $statusPesan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Peringatan Penting: Status Stok Barang SIGMA-LAB Menipis/Habis',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.barang-stok-habis',
            with: [
                'barang' => $this->barang,
                'statusPesan' => $this->statusPesan,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}