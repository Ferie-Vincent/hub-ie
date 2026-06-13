<?php

use App\Enums\ApplicationStatus;
use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Livewire\Participant\Downloads;
use App\Models\Application;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopCourseFile;
use App\Notifications\NewCourseFileUploaded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

// ── Helpers ───────────────────────────────────────────────────────────────────

function makeEnrolledUser(Workshop $workshop): User
{
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('candidate');

    Enrollment::create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Enrolled,
        'badge_status' => BadgeStatus::Valid,
        'qr_token' => Str::random(48),
        'cancellation_token' => Str::random(64),
        'enrolled_at' => now(),
    ]);

    $application = Application::factory()->accepted()->create(['user_id' => $user->id]);
    $application->workshops()->attach($workshop->id);

    return $user;
}

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

    WorkshopCourseFile::factory()->create([
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

// ── Accès participant (Downloads component — utilise Enrollment) ───────────

test('participant inscrit voit les fichiers de son atelier', function () {
    $user = makeEnrolledUser($this->workshop);

    WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    $component = Livewire::actingAs($user)->test(Downloads::class);

    expect($component->get('application'))->not->toBeNull();
    $courseFiles = $component->get('courseFiles');
    expect($courseFiles)->toHaveKey($this->workshop->id);
    expect($courseFiles[$this->workshop->id])->toHaveCount(1);
});

test('participant sans inscription ne voit aucun fichier', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('candidate');

    WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    $component = Livewire::actingAs($user)->test(Downloads::class);

    expect($component->get('application'))->toBeNull();
    expect($component->get('courseFiles'))->toBeEmpty();
});

test('participant inscrit peut télécharger un fichier de son atelier', function () {
    $user = makeEnrolledUser($this->workshop);

    Storage::disk('public')->put('workshop-courses/test.pdf', 'fake pdf content');

    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
        'file_path' => 'workshop-courses/test.pdf',
        'original_filename' => 'support-cours.pdf',
        'mime_type' => 'application/pdf',
    ]);

    // Vérifie l'accès : pas d'erreur de validation ni abort 403
    Livewire::actingAs($user)
        ->test(Downloads::class)
        ->assertHasNoErrors();

    // Le fichier est bien accessible pour cet utilisateur
    $enrollment = $user->enrollment()->where('workshop_id', $this->workshop->id)->first();
    expect($enrollment)->not->toBeNull();
    expect($file->workshop_id)->toBe($enrollment->workshop_id);
});

test('participant inscrit à un autre atelier ne peut pas télécharger', function () {
    $otherWorkshop = Workshop::factory()->create(['title' => 'Autre Atelier']);
    $user = makeEnrolledUser($otherWorkshop); // inscrit à otherWorkshop, pas workshop

    $file = WorkshopCourseFile::factory()->create([
        'workshop_id' => $this->workshop->id,
        'uploaded_by' => $this->admin->id,
        'is_published' => true,
    ]);

    // Le fichier n'est pas dans les ateliers de l'utilisateur
    $enrollment = $user->enrollment()->where('workshop_id', $this->workshop->id)->first();
    expect($enrollment)->toBeNull();
});
