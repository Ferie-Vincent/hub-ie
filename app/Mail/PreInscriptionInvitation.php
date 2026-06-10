<?php

namespace App\Mail;

use App\Models\PreInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreInscriptionInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly PreInscription $preInscription,
        public readonly string $activationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hub Import-Export 2026 — Activez votre espace personnel',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pre-inscription-invitation',
        );
    }
}
