<?php

use App\Enums\ApplicationStatus;
use App\Jobs\SendNewEditionAnnouncement;
use App\Mail\NewEditionLaunched;
use App\Models\Application;
use App\Models\Edition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);

    $this->edition = Edition::create([
        'year' => 2026,
        'title' => 'Hub Import-Export 2026',
        'theme' => "L'export au cœur du développement",
        'location' => 'Abidjan, Côte d\'Ivoire',
        'application_opens_at' => now()->subDays(30),
        'application_closes_at' => now()->addDays(15),
        'event_starts_at' => now()->addMonths(1),
        'event_ends_at' => now()->addMonths(1)->addDays(3),
        'max_participants' => 180,
        'quota_women_min_pct' => 50,
        'quota_youth_min_pct' => 40,
        'quota_youth_max_age' => 35,
        'registration_open' => true,
        'is_active' => true,
        'launched_at' => null,
    ]);
});

// ── 1. Edition::current() retourne l'édition active ───────────────────────────

test('Edition::current() retourne l\'édition active', function () {
    $current = Edition::current();

    expect($current)->not->toBeNull()
        ->and($current->id)->toBe($this->edition->id)
        ->and($current->is_active)->toBeTrue();
});

// ── 2. activate() désactive les autres éditions ───────────────────────────────

test('activate() désactive les autres éditions', function () {
    $other = Edition::create([
        'year' => 2025,
        'title' => 'Hub Import-Export 2025',
        'theme' => 'Thème 2025',
        'location' => 'Abidjan',
        'registration_open' => false,
        'is_active' => true,
        'max_participants' => 150,
        'quota_women_min_pct' => 50,
        'quota_youth_min_pct' => 40,
        'quota_youth_max_age' => 35,
    ]);

    // Activer l'édition 2026 doit désactiver 2025
    $this->edition->activate();

    expect($other->fresh()->is_active)->toBeFalse()
        ->and($this->edition->fresh()->is_active)->toBeTrue();

    // Activer 2025 doit désactiver 2026
    // Rafraîchir depuis la DB : le modèle en mémoire est obsolète après le mass-update
    $other->refresh()->activate();

    expect($this->edition->fresh()->is_active)->toBeFalse()
        ->and($other->fresh()->is_active)->toBeTrue();
});

// ── 3. isRegistrationOpen() retourne false si registration_open=false ─────────

test('isRegistrationOpen() retourne false si registration_open est false', function () {
    $this->edition->update(['registration_open' => false]);

    expect($this->edition->isRegistrationOpen())->toBeFalse();
});

// ── 4. isRegistrationOpen() retourne false si application_closes_at dans le passé

test('isRegistrationOpen() retourne false si application_closes_at est dépassée', function () {
    $this->edition->update([
        'registration_open' => true,
        'application_opens_at' => now()->subDays(30),
        'application_closes_at' => now()->subDay(),
    ]);

    expect($this->edition->isRegistrationOpen())->toBeFalse();
});

// ── 5. isRegistrationOpen() retourne true si dans les dates et ouvert ─────────

test('isRegistrationOpen() retourne true si dans la fenêtre et registration_open=true', function () {
    $this->edition->update([
        'registration_open' => true,
        'application_opens_at' => now()->subDays(5),
        'application_closes_at' => now()->addDays(10),
    ]);

    expect($this->edition->isRegistrationOpen())->toBeTrue();
});

// ── 6. Nouvelle application hérite de l'édition active (observer) ─────────────

test('une nouvelle application sans edition_id hérite de l\'édition active via l\'observer', function () {
    $user = User::factory()->create();

    $application = Application::factory()->create([
        'user_id' => $user->id,
        'edition_id' => null,
    ]);

    expect($application->fresh()->edition_id)->toBe($this->edition->id);
});

// ── 7. Application::forEdition() scope filtre par édition ─────────────────────

test('Application::forEdition() scope filtre les candidatures par édition', function () {
    $otherEdition = Edition::create([
        'year' => 2025,
        'title' => 'Hub Import-Export 2025',
        'theme' => 'Thème 2025',
        'location' => 'Abidjan',
        'registration_open' => false,
        'is_active' => false,
        'max_participants' => 150,
        'quota_women_min_pct' => 50,
        'quota_youth_min_pct' => 40,
        'quota_youth_max_age' => 35,
    ]);

    $appCurrent = Application::factory()->create(['edition_id' => $this->edition->id]);
    $appOther = Application::factory()->create(['edition_id' => $otherEdition->id]);

    $results = Application::forEdition($this->edition)->get();

    expect($results->pluck('id'))->toContain($appCurrent->id)
        ->not->toContain($appOther->id);
});

// ── 8. Application::archived() scope retourne les éditions non actives ─────────

test('Application::archived() scope retourne uniquement les candidatures hors édition active', function () {
    $otherEdition = Edition::create([
        'year' => 2025,
        'title' => 'Hub Import-Export 2025',
        'theme' => 'Thème 2025',
        'location' => 'Abidjan',
        'registration_open' => false,
        'is_active' => false,
        'max_participants' => 150,
        'quota_women_min_pct' => 50,
        'quota_youth_min_pct' => 40,
        'quota_youth_max_age' => 35,
    ]);

    $appCurrent = Application::factory()->create(['edition_id' => $this->edition->id]);
    $appArchived = Application::factory()->create(['edition_id' => $otherEdition->id]);

    $results = Application::archived()->get();

    expect($results->pluck('id'))->toContain($appArchived->id)
        ->not->toContain($appCurrent->id);
});

// ── 9. SendNewEditionAnnouncement envoie mail uniquement aux anciens participants

test('SendNewEditionAnnouncement envoie le mail uniquement aux anciens participants', function () {
    Mail::fake();

    // Edition précédente
    $previousEdition = Edition::create([
        'year' => 2024,
        'title' => 'Hub Import-Export 2024',
        'theme' => 'Thème 2024',
        'location' => 'Abidjan',
        'registration_open' => false,
        'is_active' => false,
        'max_participants' => 150,
        'quota_women_min_pct' => 50,
        'quota_youth_min_pct' => 40,
        'quota_youth_max_age' => 35,
    ]);

    // Ancien participant (application soumise dans une édition précédente) → doit recevoir le mail
    $oldParticipant = User::factory()->create(['email_verified_at' => now()]);
    Application::factory()->create([
        'user_id' => $oldParticipant->id,
        'edition_id' => $previousEdition->id,
        'submitted_at' => now()->subYear(),
        'status' => ApplicationStatus::Accepted->value,
    ]);

    // Candidat de l'édition courante → ne doit PAS recevoir le mail
    $currentCandidate = User::factory()->create(['email_verified_at' => now()]);
    Application::factory()->create([
        'user_id' => $currentCandidate->id,
        'edition_id' => $this->edition->id,
        'submitted_at' => now()->subDays(5),
        'status' => ApplicationStatus::Received->value,
    ]);

    // User sans candidature soumise → ne doit PAS recevoir le mail
    $userWithoutApplication = User::factory()->create(['email_verified_at' => now()]);

    // User avec email non vérifié → ne doit PAS recevoir le mail
    $unverifiedUser = User::factory()->unverified()->create();
    Application::factory()->create([
        'user_id' => $unverifiedUser->id,
        'edition_id' => $previousEdition->id,
        'submitted_at' => now()->subYear(),
        'status' => ApplicationStatus::Accepted->value,
    ]);

    (new SendNewEditionAnnouncement($this->edition))->handle();

    Mail::assertQueued(NewEditionLaunched::class, function ($mail) use ($oldParticipant) {
        return $mail->hasTo($oldParticipant->email);
    });

    Mail::assertNotQueued(NewEditionLaunched::class, function ($mail) use ($currentCandidate) {
        return $mail->hasTo($currentCandidate->email);
    });

    Mail::assertNotQueued(NewEditionLaunched::class, function ($mail) use ($userWithoutApplication) {
        return $mail->hasTo($userWithoutApplication->email);
    });

    Mail::assertNotQueued(NewEditionLaunched::class, function ($mail) use ($unverifiedUser) {
        return $mail->hasTo($unverifiedUser->email);
    });

    Mail::assertQueuedCount(1);
});

// ── 10. launched_at est mis à jour après envoi de l'annonce ──────────────────

test('launched_at est mis à jour après l\'exécution du job d\'annonce', function () {
    Mail::fake();

    expect($this->edition->launched_at)->toBeNull();

    (new SendNewEditionAnnouncement($this->edition))->handle();

    expect($this->edition->fresh()->launched_at)->not->toBeNull()
        ->and($this->edition->fresh()->launched_at->isToday())->toBeTrue();
});
