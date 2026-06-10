<?php

namespace App\Mail;

use App\Models\Enrollment;
use App\Services\CancellationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class WaitlistPromoted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $cancelUrl;

    public string $deadline;

    public function __construct(public Enrollment $enrollment)
    {
        $this->cancelUrl = URL::signedRoute('enrollment.cancel', [
            'token' => $enrollment->cancellation_token,
        ], now()->addDays(30));

        $this->deadline = app(CancellationService::class)->deadlineLabel();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vous êtes inscrit(e) — Hub Import-Export 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.enrollment.waitlist-promoted',
            with: [
                'user' => $this->enrollment->user,
                'workshop' => $this->enrollment->workshop,
                'cancelUrl' => $this->cancelUrl,
                'deadline' => $this->deadline,
            ],
        );
    }

    public function attachments(): array
    {
        if (
            $this->enrollment->badge_path &&
            Storage::disk('local')->exists($this->enrollment->badge_path)
        ) {
            return [
                Attachment::fromStorageDisk('local', $this->enrollment->badge_path)
                    ->as("badge-inscription-{$this->enrollment->id}.pdf")
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
