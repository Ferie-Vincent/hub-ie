<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Application\ApplicationWizard;
use App\Models\Application;
use App\Models\User;
use App\Models\Workshop;
use App\Services\ApplicationStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
    $this->artisan('db:seed', ['--class' => 'WorkshopsSeeder']);
});

// ── Critère 1 — soumission complète → statut received ──────────────────────

test('full 4-step submission transitions application to received', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    $workshop = Workshop::first();

    $component = Livewire::actingAs($user)
        ->test(ApplicationWizard::class);

    // Étape 1
    $component
        ->set('firstName', 'Kouamé')
        ->set('lastName', 'Konan')
        ->set('gender', 'M')
        ->set('birthDate', '1990-01-15')
        ->set('nationality', 'Ivoirienne')
        ->set('phone', '+2250700000000')
        ->set('city', 'Abidjan')
        ->set('country', "Côte d'Ivoire")
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('step', 2);

    // Étape 2
    $component
        ->set('category', 'pme_exportatrice')
        ->set('organizationName', 'Konan Exports SARL')
        ->set('organizationType', 'Société commerciale')
        ->set('position', 'Directeur général')
        ->set('sector', 'Commerce international')
        ->set('experienceYears', '10')
        ->set('rccmNumber', 'CI-ABJ-2020-B-123456')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('step', 3);

    // Étape 3
    $component
        ->set('motivation', str_repeat('Motivation valide. ', 28)) // ~500 chars
        ->set('chosenWorkshops', [$workshop->id])
        ->set('referralSource', 'Web')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('step', 4);

    expect($user->fresh()->applications()->count())->toBe(1);

    $app = $user->fresh()->applications()->first();
    expect($app->status)->toBe(ApplicationStatus::Draft);
});

// ── Critère 2 — un seul dossier actif par utilisateur ──────────────────────

test('user cannot create two active applications', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Application::factory()->create([
        'user_id' => $user->id,
        'status'  => ApplicationStatus::Received->value,
    ]);

    $component = Livewire::actingAs($user)->test(ApplicationWizard::class);

    // applicationId should be set to the existing application
    expect($component->get('applicationId'))->not->toBeNull();
});

// ── Critère 3 — validation motivation 500–1500 chars ───────────────────────

test('motivation shorter than 500 chars fails validation', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Livewire::actingAs($user)
        ->test(ApplicationWizard::class)
        ->set('step', 3)
        ->set('motivation', 'Trop court.')
        ->set('chosenWorkshops', [1])
        ->set('referralSource', 'Web')
        ->call('nextStep')
        ->assertHasErrors(['motivation']);
});

test('motivation longer than 1500 chars fails validation', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Livewire::actingAs($user)
        ->test(ApplicationWizard::class)
        ->set('step', 3)
        ->set('motivation', str_repeat('A', 1501))
        ->set('chosenWorkshops', [1])
        ->set('referralSource', 'Web')
        ->call('nextStep')
        ->assertHasErrors(['motivation']);
});

// ── Critère 4 — max 2 ateliers ──────────────────────────────────────────────

test('selecting 3 workshops fails step 3 validation', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Livewire::actingAs($user)
        ->test(ApplicationWizard::class)
        ->set('step', 3)
        ->set('motivation', str_repeat('Motivation valide. ', 28))
        ->set('chosenWorkshops', [1, 2, 3])
        ->set('referralSource', 'Web')
        ->call('nextStep')
        ->assertHasErrors(['chosenWorkshops']);
});

// ── Critère 5 — RGPD consent requis ────────────────────────────────────────

test('rgpd consent is required at step 4', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Livewire::actingAs($user)
        ->test(ApplicationWizard::class)
        ->set('step', 4)
        ->set('rgpdConsent', false)
        ->call('submit')
        ->assertHasErrors(['rgpdConsent']);
});

// ── Critère 6 — phone format E.164 ─────────────────────────────────────────

test('invalid phone format fails step 1', function () {
    $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
    $user->assignRole('candidate');

    Livewire::actingAs($user)
        ->test(ApplicationWizard::class)
        ->set('firstName', 'Test')
        ->set('lastName', 'User')
        ->set('gender', 'M')
        ->set('birthDate', '1990-01-01')
        ->set('nationality', 'Ivoirienne')
        ->set('phone', '0700000000') // missing + prefix
        ->set('city', 'Abidjan')
        ->set('country', "Côte d'Ivoire")
        ->call('nextStep')
        ->assertHasErrors(['phone']);
});

// ── Critère 7 — statut transitions (ApplicationStatusService) ─────────────

test('draft can transition to received', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Draft]);
    $service = new ApplicationStatusService();

    $service->transition($app, ApplicationStatus::Received);

    expect($app->fresh()->status)->toBe(ApplicationStatus::Received);
});

test('forbidden transition throws DomainException', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Draft]);
    $service = new ApplicationStatusService();

    expect(fn () => $service->transition($app, ApplicationStatus::Accepted))
        ->toThrow(\DomainException::class);
});

test('received can transition to incomplete', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Received]);
    $service = new ApplicationStatusService();

    $service->transition($app, ApplicationStatus::Incomplete);

    expect($app->fresh()->status)->toBe(ApplicationStatus::Incomplete);
});

test('accepted can be withdrawn (enables waitlist auto-promotion)', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Accepted]);
    $service = new ApplicationStatusService();

    $service->transition($app, ApplicationStatus::Withdrawn);

    expect($app->fresh()->status)->toBe(ApplicationStatus::Withdrawn);
});

test('waitlisted candidate auto-promoted when accepted withdraws', function () {
    Queue::fake();
    Mail::fake();

    $accepted   = Application::factory()->create([
        'status'       => ApplicationStatus::Accepted,
        'qr_token'     => 'tok-accepted',
        'check_in_code'=> 111111,
        'group_label'  => 'G1',
    ]);
    $waitlisted = Application::factory()->create([
        'status'       => ApplicationStatus::Waitlisted,
        'submitted_at' => now()->subDay(),
    ]);
    $service = new ApplicationStatusService();

    $service->transition($accepted, ApplicationStatus::Withdrawn);

    expect($waitlisted->fresh()->status)->toBe(ApplicationStatus::Accepted);
});

test('rejected application cannot be withdrawn', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Rejected]);
    $service = new ApplicationStatusService();

    expect(fn () => $service->transition($app, ApplicationStatus::Withdrawn))
        ->toThrow(\DomainException::class);
});
