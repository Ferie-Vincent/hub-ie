<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Participant\Messages;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use App\Models\Workshop;
use App\Notifications\NewMessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
    Notification::fake();

    $this->workshop = Workshop::factory()->create(['title' => 'Atelier Export']);

    $this->trainer = User::factory()->create(['first_name' => 'Formateur', 'last_name' => 'Test', 'email_verified_at' => now()]);
    $this->trainer->assignRole('super_admin');

    $this->participant = User::factory()->create(['email_verified_at' => now()]);
    $this->participant->assignRole('candidate');

    $this->application = Application::factory()->create([
        'user_id' => $this->participant->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    $this->application->workshops()->attach($this->workshop->id);

    $this->conversation = Conversation::create([
        'application_id' => $this->application->id,
        'workshop_id' => $this->workshop->id,
        'participant_user_id' => $this->participant->id,
        'trainer_user_id' => $this->trainer->id,
        'subject' => 'Discussion atelier',
        'is_closed' => false,
    ]);
});

// ── Messagerie participant ─────────────────────────────────────────────────

test('participant peut envoyer un message', function () {
    Livewire::actingAs($this->participant)
        ->test(Messages::class)
        ->call('selectConversation', $this->conversation->id)
        ->set('newMessage', 'Bonjour, j\'ai une question.')
        ->call('sendMessage')
        ->assertHasNoErrors();

    expect(ConversationMessage::where('conversation_id', $this->conversation->id)->count())->toBe(1);
});

test('message vide est rejeté', function () {
    Livewire::actingAs($this->participant)
        ->test(Messages::class)
        ->call('selectConversation', $this->conversation->id)
        ->set('newMessage', '')
        ->call('sendMessage')
        ->assertHasErrors(['newMessage' => 'required']);
});

test('message trop court est rejeté', function () {
    Livewire::actingAs($this->participant)
        ->test(Messages::class)
        ->call('selectConversation', $this->conversation->id)
        ->set('newMessage', 'Hi')
        ->call('sendMessage')
        ->assertHasErrors(['newMessage' => 'min']);
});

// ── Notifications ─────────────────────────────────────────────────────────

test('formateur reçoit notification quand participant envoie message', function () {
    ConversationMessage::create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->participant->id,
        'body' => 'Bonjour formateur, j\'ai une question sur le cours.',
    ]);

    Notification::assertSentTo($this->trainer, NewMessageReceived::class);
});

test('participant reçoit notification quand formateur répond', function () {
    ConversationMessage::create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->trainer->id,
        'body' => 'Bonjour, je vous réponds avec plaisir.',
    ]);

    Notification::assertSentTo($this->participant, NewMessageReceived::class);
});

// ── Lecture des messages ───────────────────────────────────────────────────

test('messages sont marqués comme lus quand participant ouvre conversation', function () {
    $msg = ConversationMessage::create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->trainer->id,
        'body' => 'Message non lu du formateur.',
        'read_at' => null,
    ]);

    expect($msg->read_at)->toBeNull();

    Livewire::actingAs($this->participant)
        ->test(Messages::class)
        ->call('selectConversation', $this->conversation->id);

    expect($msg->fresh()->read_at)->not->toBeNull();
});

// ── Conversation fermée ────────────────────────────────────────────────────

test('conversation fermée empêche nouveaux messages', function () {
    $this->conversation->update(['is_closed' => true]);

    Livewire::actingAs($this->participant)
        ->test(Messages::class)
        ->call('selectConversation', $this->conversation->id)
        ->set('newMessage', 'Je veux envoyer un message malgré la fermeture.')
        ->call('sendMessage')
        ->assertHasErrors('newMessage');

    expect(ConversationMessage::where('conversation_id', $this->conversation->id)->count())->toBe(0);
});

// ── markAsRead helper ──────────────────────────────────────────────────────

test('ConversationMessage::markAsRead ne réécrit pas read_at si déjà lu', function () {
    $msg = ConversationMessage::create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->trainer->id,
        'body' => 'Test.',
        'read_at' => $original = now()->subHour(),
    ]);

    $msg->markAsRead();

    expect($msg->fresh()->read_at->timestamp)->toBe($original->timestamp);
});
