<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail — Hub Import-Export 2026')
            ->view('emails.verify-email', ['url' => $url]);
    }
}
