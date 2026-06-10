<?php

use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
});

test('/register route no longer exists', function () {
    $this->get('/register')->assertNotFound();
});

test('invitation page renders with valid token', function () {
    $inviter = User::factory()->create();

    $invitation = AdminInvitation::create([
        'email' => 'newadmin@hubimportexport.ci',
        'role' => 'committee_member',
        'token' => 'validtoken123',
        'invited_by' => $inviter->id,
        'expires_at' => now()->addDays(7),
    ]);

    $this->get("/invitation/{$invitation->token}")
        ->assertOk()
        ->assertSee('validtoken123', false)
        ->assertSee('newadmin@hubimportexport.ci');
});

test('expired invitation redirects to login with error', function () {
    $inviter = User::factory()->create();

    $invitation = AdminInvitation::create([
        'email' => 'expired@hubimportexport.ci',
        'role' => 'committee_member',
        'token' => 'expiredtoken',
        'invited_by' => $inviter->id,
        'expires_at' => now()->subDay(),
    ]);

    $this->get("/invitation/{$invitation->token}")
        ->assertRedirect(route('login'));
});

test('invitation creates admin user with correct role', function () {
    $inviter = User::factory()->create();

    $invitation = AdminInvitation::create([
        'email' => 'collab@hubimportexport.ci',
        'role' => 'committee_member',
        'token' => 'goodtoken456',
        'invited_by' => $inviter->id,
        'expires_at' => now()->addDays(7),
    ]);

    $this->post("/invitation/{$invitation->token}", [
        'first_name' => 'Kouamé',
        'last_name' => 'DIALLO',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertRedirect('/admin');

    $this->assertAuthenticated();

    $user = User::where('email', 'collab@hubimportexport.ci')->first();
    expect($user)->not->toBeNull()
        ->and($user->hasRole('committee_member'))->toBeTrue()
        ->and($user->first_name)->toBe('Kouamé')
        ->and($user->email_verified_at)->not->toBeNull();

    expect($invitation->fresh()->accepted_at)->not->toBeNull();
});

test('accepted invitation cannot be reused', function () {
    $inviter = User::factory()->create();

    $invitation = AdminInvitation::create([
        'email' => 'used@hubimportexport.ci',
        'role' => 'reader',
        'token' => 'usedtoken789',
        'invited_by' => $inviter->id,
        'expires_at' => now()->addDays(7),
        'accepted_at' => now()->subHour(),
    ]);

    $this->get("/invitation/{$invitation->token}")
        ->assertRedirect(route('login'));
});
