<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ApplicationAccepted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Félicitations — Convocation officielle au Hub Import-Export 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.application.accepted',
            with: ['user' => $this->application->user],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->application->badge_path && Storage::disk('local')->exists($this->application->badge_path)) {
            $attachments[] = Attachment::fromStorageDisk('local', $this->application->badge_path)
                ->as("badge-{$this->application->reference_code}.pdf")
                ->withMime('application/pdf');
        }

        $convPath = "convocations/convocation-{$this->application->reference_code}.pdf";
        if (Storage::disk('local')->exists($convPath)) {
            $attachments[] = Attachment::fromStorageDisk('local', $convPath)
                ->as("convocation-{$this->application->reference_code}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
