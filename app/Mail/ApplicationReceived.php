<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre candidature au Hub Import-Export 2026 a bien été reçue',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.application.received',
            with: [
                'application' => $this->application,
                'user' => $this->application->user,
            ],
        );
    }
}
