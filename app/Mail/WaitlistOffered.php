<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitlistOffered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Une place est disponible — Hub Import-Export 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.enrollment.waitlist-offered',
            with: [
                'user' => $this->enrollment->user,
                'workshop' => $this->enrollment->workshop,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
