<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ConversationMessage $message,
        public readonly Conversation $conversation,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->conversation->subject
            ?? ($this->conversation->workshop?->title ?? 'Discussion Hub Import-Export');

        $preview = mb_strlen($this->message->body) > 200
            ? mb_substr($this->message->body, 0, 200).'…'
            : $this->message->body;

        $senderName = $this->message->sender?->name ?? 'Un participant';

        $url = $notifiable->hasRole('super_admin') || $notifiable->hasRole('admin')
            ? route('filament.admin.resources.conversations.index')
            : route('participant.messages');

        return (new MailMessage)
            ->subject('Nouveau message — '.$subject)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('**'.$senderName.'** vous a envoyé un message.')
            ->line('> '.$preview)
            ->action('Voir la conversation', $url);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_message',
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_name' => $this->message->sender?->name,
            'preview' => mb_substr($this->message->body, 0, 120),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
