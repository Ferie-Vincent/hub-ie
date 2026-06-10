<?php

namespace App\Mail;

use App\Models\AdminInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInvited extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly AdminInvitation $invitation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation à rejoindre le Hub Import-Export 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin-invited',
            with: [
                'acceptUrl' => route('invitation.show', $this->invitation->token),
                'inviterName' => $this->invitation->invitedBy->full_name,
                'role' => $this->invitation->role,
                'expiresAt' => $this->invitation->expires_at->format('d/m/Y à H:i'),
            ],
        );
    }
}
