<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
});

test('candidate cannot access admin panel', function () {
    $candidate = User::factory()->create(['is_active' => true]);
    $candidate->assignRole('candidate');

    // Filament throws AuthorizationException (403) when canAccessPanel() returns false
    $this->actingAs($candidate)
        ->get('/admin')
        ->assertForbidden();
});

test('committee_member can access admin panel', function () {
    $member = User::factory()->create(['is_active' => true]);
    $member->assignRole('committee_member');

    $this->actingAs($member)
        ->get('/admin')
        ->assertOk();
});

test('agent_entry can access admin panel', function () {
    $agent = User::factory()->create(['is_active' => true]);
    $agent->assignRole('agent_entry');

    $this->actingAs($agent)
        ->get('/admin')
        ->assertOk();
});

test('committee_member cannot accept applications', function () {
    $member = User::factory()->create(['is_active' => true]);
    $member->assignRole('committee_member');

    expect($member->can('accept-applications'))->toBeFalse();
});

test('committee_president can accept applications', function () {
    $president = User::factory()->create(['is_active' => true]);
    $president->assignRole('committee_president');

    expect($president->can('accept-applications'))->toBeTrue();
});

test('super_admin can accept applications', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super_admin');

    expect($admin->can('accept-applications'))->toBeTrue();
});

test('candidate cannot access candidature wizard without verification', function () {
    $candidate = User::factory()->create(['is_active' => true, 'email_verified_at' => null]);
    $candidate->assignRole('candidate');

    $this->actingAs($candidate)
        ->get('/candidature')
        ->assertRedirect(route('verification.notice'));
});

test('candidate can access candidature wizard when verified', function () {
    $candidate = User::factory()->create(['is_active' => true]);
    $candidate->assignRole('candidate');

    $this->actingAs($candidate)
        ->get('/candidature')
        ->assertOk();
});

test('staff member is redirected away from candidature', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('admin_dgce');

    $this->actingAs($admin)
        ->get('/candidature')
        ->assertRedirect();
});

test('direct POST to /register returns 404', function () {
    $this->post('/register', [
        'first_name' => 'Konan',
        'last_name' => 'Yao',
        'email' => 'konan@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertNotFound();
});
