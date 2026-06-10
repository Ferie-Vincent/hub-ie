<?php

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Mail\WaitlistPromoted;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use App\Services\CancellationService;
use App\Services\EnrollmentService;
use App\Services\WaitlistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

// ── Helpers ──────────────────────────────────────────────────────────────────

function makeWorkshop(int $capacity = 5): Workshop
{
    return Workshop::factory()->create([
        'capacity' => $capacity,
        'registered_count' => 0,
    ]);
}

// ── Tests ─────────────────────────────────────────────────────────────────────

it('invalide le badge lors de la désinscription avant J-1', function () {
    Mail::fake();
    Bus::fake();

    $user = User::factory()->create();
    $workshop = makeWorkshop();

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Enrolled,
        'badge_status' => BadgeStatus::Valid,
        'qr_token' => 'tok_valid_123',
        'cancellation_token' => 'cancel_tok_123',
        'enrolled_at' => now(),
    ]);
    $workshop->increment('registered_count');

    // Simule qu'on est avant J-1
    $this->travel(-5)->days();

    app(CancellationService::class)->cancelByToken('cancel_tok_123');

    $enrollment->refresh();
    expect($enrollment->status)->toBe(EnrollmentStatus::Cancelled)
        ->and($enrollment->badge_status)->toBe(BadgeStatus::Invalid)
        ->and($enrollment->cancelled_at)->not->toBeNull();
    expect($workshop->fresh()->registered_count)->toBe(0);
});

it('refuse la désinscription après J-1', function () {
    $user = User::factory()->create();
    $workshop = makeWorkshop();

    Enrollment::create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Enrolled,
        'badge_status' => BadgeStatus::Valid,
        'qr_token' => 'tok_late_123',
        'cancellation_token' => 'cancel_late_123',
        'enrolled_at' => now(),
    ]);

    // Simule qu'on est le jour J ou après
    $this->travel(15)->days();

    expect(fn () => app(CancellationService::class)->cancelByToken('cancel_late_123'))
        ->toThrow(RuntimeException::class, 'délai de désinscription');
});

it('envoie un seul email quand plusieurs places se libèrent simultanément', function () {
    Mail::fake();
    Bus::fake();

    $workshop = makeWorkshop(1);
    $workshop->update(['registered_count' => 1]); // session pleine

    // 2 waitlistés — cancellation_token requis par WaitlistPromoted Mailable
    $w1 = Enrollment::create([
        'user_id' => User::factory()->create()->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Waitlisted,
        'cancellation_token' => Str::random(64),
        'created_at' => now()->subMinutes(10),
    ]);
    $w2 = Enrollment::create([
        'user_id' => User::factory()->create()->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Waitlisted,
        'cancellation_token' => Str::random(64),
        'created_at' => now()->subMinutes(5),
    ]);

    // Une seule place libérée
    $workshop->decrement('registered_count');
    app(WaitlistService::class)->promoteNext($workshop);

    // Seul le premier waitlisté est promu
    expect($w1->fresh()->status)->toBe(EnrollmentStatus::Enrolled);
    expect($w2->fresh()->status)->toBe(EnrollmentStatus::Waitlisted);

    // Un seul mail de promotion envoyé
    Mail::assertQueued(WaitlistPromoted::class, 1);
});

it('log la force-inscription admin avec acteur', function () {
    Bus::fake();
    Mail::fake();

    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $user = User::factory()->create();
    $workshop = makeWorkshop(2);

    $this->actingAs($admin);

    $enrollment = app(EnrollmentService::class)->forceEnroll($user, $workshop, $admin);

    $this->assertDatabaseHas('audit_logs', [
        'subject_type' => Enrollment::class,
        'subject_id' => $enrollment->id,
        'action' => 'force_enrolled',
        'user_id' => $admin->id,
    ]);
});
