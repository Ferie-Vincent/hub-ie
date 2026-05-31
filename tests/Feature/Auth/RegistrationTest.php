<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
});

test('registration screen can be rendered', function () {
    $this->get('/register')->assertStatus(200);
});

test('new users can register with first and last name', function () {
    $this->post('/register', [
        'first_name' => 'Konan',
        'last_name' => 'Yao',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertRedirect(route('verification.notice'));

    $this->assertAuthenticated();
});
