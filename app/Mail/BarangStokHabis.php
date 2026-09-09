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

    public function __construct($barang)
    {
        $this->barang = $barang;
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
            view: 'emails.barang-stok-habis', // Ini nama file view Blade email kita nanti
        );
    }

    public function attachments(): array
    {
        return [];
    }
}