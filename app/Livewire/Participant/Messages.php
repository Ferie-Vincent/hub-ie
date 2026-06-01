<?php

namespace App\Livewire\Participant;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Workshop;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Messages extends Component
{
    public Collection $conversations;

    public ?int $activeConversation = null;

    public array $activeMessages = [];

    public string $newMessage = '';

    public ?int $workshopId = null;

    public function mount(): void
    {
        $this->conversations = collect();
        $this->loadConversations();
    }

    private function loadConversations(): void
    {
        $application = $this->getParticipantApplication();

        if (! $application) {
            return;
        }

        $this->conversations = Conversation::where('application_id', $application->id)
            ->with([
                'messages' => fn ($q) => $q->orderBy('created_at'),
                'application',
                'workshop',
                'trainer',
            ])
            ->orderByDesc('last_message_at')
            ->get();
    }

    public function selectConversation(int $id): void
    {
        $this->activeConversation = $id;

        // Server-side ownership check (defence-in-depth beyond in-memory collection)
        $application = $this->getParticipantApplication();
        $conversation = $application
            ? Conversation::where('id', $id)->where('application_id', $application->id)->first()
            : null;

        if (! $conversation) {
            return;
        }

        $userId = auth()->id();

        // Mark unread messages as read
        ConversationMessage::where('conversation_id', $id)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Reload messages with fresh data
        $this->activeMessages = ConversationMessage::where('conversation_id', $id)
            ->orderBy('created_at')
            ->get()
            ->toArray();

        // Reload conversations to refresh unread counts
        $this->loadConversations();

        $this->dispatch('conversation-selected');
    }

    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|min:3|max:2000',
        ], [
            'newMessage.required' => 'Veuillez saisir un message.',
            'newMessage.min' => 'Le message doit contenir au moins 3 caractères.',
            'newMessage.max' => 'Le message ne peut pas dépasser 2000 caractères.',
        ]);

        if (! $this->activeConversation) {
            return;
        }

        $application = $this->getParticipantApplication();

        if (! $application) {
            return;
        }

        // IDOR fix: scope conversation to the participant's own application
        $conversation = Conversation::where('id', $this->activeConversation)
            ->where('application_id', $application->id)
            ->first();

        if (! $conversation || $conversation->is_closed) {
            $this->addError('newMessage', 'Cette conversation est fermée ou introuvable.');

            return;
        }

        ConversationMessage::create([
            'conversation_id' => $this->activeConversation,
            'sender_id' => auth()->id(),
            'body' => $this->newMessage,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $this->reset('newMessage');

        // Reload messages
        $this->activeMessages = ConversationMessage::where('conversation_id', $this->activeConversation)
            ->orderBy('created_at')
            ->get()
            ->toArray();

        // Reload conversations list to update preview
        $this->loadConversations();

        $this->dispatch('message-sent');

        // Notify trainer
        $this->notifyTrainer($conversation);
    }

    public function startConversation(int $workshopId): void
    {
        $application = $this->getParticipantApplication();

        if (! $application) {
            session()->flash('error', 'Candidature introuvable.');

            return;
        }

        $workshop = Workshop::find($workshopId);

        if (! $workshop) {
            session()->flash('error', 'Atelier introuvable.');

            return;
        }

        // Enrollment check: participant must be enrolled in this workshop
        if (! $application->workshops()->where('workshops.id', $workshopId)->exists()) {
            session()->flash('error', 'Vous n\'êtes pas inscrit à cet atelier.');

            return;
        }

        $conversation = Conversation::firstOrCreate(
            [
                'application_id' => $application->id,
                'workshop_id' => $workshopId,
            ],
            [
                'trainer_user_id' => $workshop->trainer_user_id ?? null,
                'subject' => 'Discussion — '.$workshop->title,
                'is_closed' => false,
                'last_message_at' => now(),
            ]
        );

        $this->loadConversations();
        $this->selectConversation($conversation->id);
        $this->workshopId = null;
    }

    public function unreadCount(): int
    {
        $application = $this->getParticipantApplication();

        if (! $application) {
            return 0;
        }

        $conversationIds = Conversation::where('application_id', $application->id)
            ->pluck('id');

        return ConversationMessage::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    private function getParticipantApplication(): ?Application
    {
        return Application::where('user_id', auth()->id())->first();
    }

    private function notifyTrainer(Conversation $conversation): void
    {
        if (! $conversation->trainer_user_id) {
            return;
        }

        // Dispatch a browser event that can be consumed by a notification channel
        // or a server-side event/notification system
        $this->dispatch('trainer-notification', [
            'trainer_id' => $conversation->trainer_user_id,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function render(): View
    {
        $participantWorkshops = collect();
        $application = $this->getParticipantApplication();

        if ($application) {
            $participantWorkshops = $application->workshops()->get();
        }

        return view('livewire.participant.messages', [
            'participantWorkshops' => $participantWorkshops,
            'unreadTotal' => $this->unreadCount(),
        ]);
    }
}
