<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Participant\Downloads;
use App\Models\Application;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopCourseFile;
use App\Notifications\NewCourseFileUploaded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
    Storage::fake('public');
    Notification::fake();

    $this->workshop = Workshop::factory()->create(['title' => 'Atelier Import']);
    $this->admin = User::factory()->create(['email_verified_at' => now()]);
    $this->admin->assignRole('super_admin');
});

// ── Upload ────────────────────────────────────────────────────────────────

test('admin peut créer un fichier de cours', function () {
    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'title' => 'Support de cours PDF',
        'is_published' => true,
    ]);

    expect($file)->toBeInstanceOf(WorkshopCourseFile::class)
        ->and($file->workshop_id)->toBe($this->workshop->id)
        ->and($file->title)->toBe('Support de cours PDF');
});

test('file_size_human retourne format lisible', function () {
    $file = WorkshopCourseFile::factory()->make(['file_size_bytes' => 1048576]);
    expect($file->file_size_human)->toBe('1.00 Mo');

    $file->file_size_bytes = 512;
    expect($file->file_size_human)->toBe('512 octets');

    $file->file_size_bytes = 2048;
    expect($file->file_size_human)->toBe('2.00 Ko');
});

// ── Notification participants ─────────────────────────────────────────────

test('participants acceptés de l\'atelier reçoivent notification quand fichier ajouté', function () {
    $acceptedUser = User::factory()->create(['email_verified_at' => now()]);
    $acceptedUser->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $acceptedUser->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    $application->workshops()->attach($this->workshop->id);

    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    Notification::assertSentTo($acceptedUser, NewCourseFileUploaded::class);
});

test('participants non acceptés ne reçoivent pas de notification', function () {
    $pendingUser = User::factory()->create(['email_verified_at' => now()]);
    $pendingUser->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $pendingUser->id,
        'status' => ApplicationStatus::UnderReview,
    ]);
    $application->workshops()->attach($this->workshop->id);

    WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    Notification::assertNothingSentTo($pendingUser);
});

test('fichier non publié ne déclenche pas de notification', function () {
    $acceptedUser = User::factory()->create(['email_verified_at' => now()]);
    $acceptedUser->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $acceptedUser->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    $application->workshops()->attach($this->workshop->id);

    WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => false,
    ]);

    Notification::assertNothingSentTo($acceptedUser);
});

// ── Accès participant ──────────────────────────────────────────────────────

test('participant accepté voit les fichiers de ses ateliers', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $user->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    $application->workshops()->attach($this->workshop->id);

    WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    Livewire::actingAs($user)
        ->test(Downloads::class)
        ->assertSet('application.id', $application->id)
        ->assertCount('courseFiles.'.$this->workshop->id, 1);
});

test('participant peut télécharger fichier de son atelier', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $user->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    $application->workshops()->attach($this->workshop->id);

    Storage::disk('public')->put('workshop-courses/test.pdf', 'fake pdf content');

    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
        'file_path' => 'workshop-courses/test.pdf',
        'original_filename' => 'support-cours.pdf',
        'mime_type' => 'application/pdf',
    ]);

    Livewire::actingAs($user)
        ->test(Downloads::class)
        ->call('download', $file->id)
        ->assertStatus(200);
});

test('participant ne peut pas télécharger fichier hors de ses ateliers', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('candidate');
    $application = Application::factory()->create([
        'user_id' => $user->id,
        'status' => ApplicationStatus::Accepted,
    ]);
    // N'attache PAS l'atelier

    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    Livewire::actingAs($user)
        ->test(Downloads::class)
        ->call('download', $file->id)
        ->assertStatus(404);
});
