<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationWaitlisted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Liste d\'attente — Hub Import-Export 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.application.waitlisted',
            with: ['user' => $this->application->user],
        );
    }
}
