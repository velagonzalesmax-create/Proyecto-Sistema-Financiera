<?php

namespace App\Mail;

use App\Models\PagoRecurrente;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoPorVencerMail extends Mailable
{
    use Queueable, SerializesModels;

    public PagoRecurrente $pago;

    /**
     * Create a new message instance.
     */
    public function __construct(PagoRecurrente $pago)
    {
        $this->pago = $pago;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso: Pago de ' . $this->pago->nombre . ' próximo a vencer',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pago_por_vencer',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
