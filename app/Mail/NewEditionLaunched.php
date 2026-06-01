<?php

namespace App\Mail;

use App\Models\Edition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEditionLaunched extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Edition $edition,
        public readonly User $recipient,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Hub Import-Export '.$this->edition->year.' — Les candidatures sont ouvertes !',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.editions.new-edition-launched',
            with: [
                'edition' => $this->edition,
                'recipient' => $this->recipient,
            ],
        );
    }
}
