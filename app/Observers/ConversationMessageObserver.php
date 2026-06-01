<?php

namespace App\Observers;

use App\Models\ConversationMessage;
use App\Notifications\NewMessageReceived;

class ConversationMessageObserver
{
    public function created(ConversationMessage $message): void
    {
        $conversation = $message->conversation()->with(['participant', 'trainer'])->first();

        if (! $conversation) {
            return;
        }

        $conversation->update(['last_message_at' => now()]);

        $recipient = $message->sender_id === $conversation->participant_user_id
            ? $conversation->trainer
            : $conversation->participant;

        if ($recipient && $recipient->id !== $message->sender_id) {
            $recipient->notify(new NewMessageReceived($message, $conversation));
        }
    }
}
