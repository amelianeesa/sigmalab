<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class HargaBarangBelumAda extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Collection $barang)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Harga Barang/Bahan Belum Diisi',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.harga-barang-belum-ada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}