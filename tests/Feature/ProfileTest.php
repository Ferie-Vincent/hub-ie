<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch('/profile', [
            'first_name' => 'Konan',
            'last_name'  => 'Yao',
            'email'      => 'konan@example.com',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    expect($user->first_name)->toBe('Konan')
        ->and($user->last_name)->toBe('Yao')
        ->and($user->email)->toBe('konan@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch('/profile', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete('/profile', ['password' => 'password'])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    // User model has SoftDeletes — record is soft-deleted, not hard-deleted
    expect($user->fresh())->not->toBeNull()
        ->and($user->fresh()->deleted_at)->not->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from('/profile')
        ->delete('/profile', ['password' => 'wrong-password'])
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    expect($user->fresh())->not->toBeNull()
        ->and($user->fresh()->deleted_at)->toBeNull();
});
