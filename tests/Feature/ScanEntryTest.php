<?php

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Filament\Pages\ScanEntry;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function makeEnrolledEnrollment(string $qrToken = 'test_qr_tok_abc123', int $checkInCode = 456789): Enrollment
{
    $user = User::factory()->create(['first_name' => 'Jean', 'last_name' => 'Dupont']);
    $workshop = Workshop::factory()->create(['title' => 'Atelier Commerce']);

    return Enrollment::create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Enrolled,
        'badge_status' => BadgeStatus::Valid,
        'qr_token' => $qrToken,
        'check_in_code' => $checkInCode,
        'cancellation_token' => 'cancel_test_'.uniqid(),
        'enrolled_at' => now(),
    ]);
}

function makeScanOperator(): User
{
    Permission::firstOrCreate(['name' => 'scan-attendance', 'guard_name' => 'web']);
    $role = Role::firstOrCreate(['name' => 'operateur', 'guard_name' => 'web']);
    $role->givePermissionTo('scan-attendance');
    $user = User::factory()->create();
    $user->assignRole('operateur');

    return $user;
}

// ── Tests ─────────────────────────────────────────────────────────────────────

it('enregistre le pointage via code 6 chiffres', function () {
    $enrollment = makeEnrolledEnrollment(checkInCode: 456789);
    $operator = makeScanOperator();

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->set('manualCode', '456789')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'success');

    expect(Attendance::where('enrollment_id', $enrollment->id)->count())->toBe(1);
});

it('enregistre le pointage via QR token', function () {
    $enrollment = makeEnrolledEnrollment(qrToken: 'test_qr_tok_abc123');
    $operator = makeScanOperator();

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->call('processQrToken', 'test_qr_tok_abc123')
        ->assertSet('scanResult', 'success');

    expect(Attendance::where('enrollment_id', $enrollment->id)->count())->toBe(1);
});

it('refuse double pointage le même jour via code', function () {
    $enrollment = makeEnrolledEnrollment(checkInCode: 456789);
    $operator = makeScanOperator();

    // Premier scan valide
    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->set('manualCode', '456789')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'success');

    // Deuxième scan le même jour → warning
    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->set('manualCode', '456789')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'warning');

    expect(Attendance::where('enrollment_id', $enrollment->id)->count())->toBe(1);
});

it('refuse double pointage le même jour via QR', function () {
    $enrollment = makeEnrolledEnrollment(qrToken: 'test_qr_tok_abc123');
    $operator = makeScanOperator();

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->call('processQrToken', 'test_qr_tok_abc123')
        ->assertSet('scanResult', 'success');

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->call('processQrToken', 'test_qr_tok_abc123')
        ->assertSet('scanResult', 'warning');

    expect(Attendance::where('enrollment_id', $enrollment->id)->count())->toBe(1);
});

it('rejette code inconnu', function () {
    $operator = makeScanOperator();

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->set('manualCode', '999999')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'error');

    expect(Attendance::count())->toBe(0);
});

it('rejette code invalide (pas 6 chiffres)', function () {
    $operator = makeScanOperator();

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->set('manualCode', '9999')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'error');
});

it('rejette QR avec badge invalidé', function () {
    $user = User::factory()->create();
    $workshop = Workshop::factory()->create();
    $operator = makeScanOperator();

    Enrollment::create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'status' => EnrollmentStatus::Cancelled,
        'badge_status' => BadgeStatus::Invalid,
        'qr_token' => 'tok_invalid_badge',
        'cancellation_token' => 'cancel_inv_'.uniqid(),
        'enrolled_at' => now(),
        'cancelled_at' => now(),
    ]);

    Livewire::actingAs($operator)
        ->test(ScanEntry::class)
        ->call('processQrToken', 'tok_invalid_badge')
        ->assertSet('scanResult', 'error');

    expect(Attendance::count())->toBe(0);
});
