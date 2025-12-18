<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $expiring14;

    public $expiring30;

    public function __construct($expiring14, $expiring30)
    {
        $this->expiring14 = $expiring14;
        $this->expiring30 = $expiring30;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Alerta de Vencimento: Produtos Expirando em Breve',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
        );
    }
}
