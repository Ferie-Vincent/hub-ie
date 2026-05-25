<?php

use App\Enums\ApplicationStatus;
use App\Enums\AttendanceLocation;
use App\Enums\AttendanceScanMethod;
use App\Filament\Pages\ScanEntry;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesSeeder']);
});

// ── Critère : un candidat ne peut être pointé qu'une fois par jour ────────────

test('duplicate check-in on same day is blocked with warning status', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('agent_entry');

    $app = Application::factory()->create([
        'status'        => ApplicationStatus::Accepted->value,
        'check_in_code' => 123456,
        'group_label'   => 'G1',
    ]);

    Attendance::create([
        'application_id'     => $app->id,
        'event_date'         => today(),
        'scanned_at'         => now(),
        'scanned_by_user_id' => $user->id,
        'location'           => AttendanceLocation::Cgeci->value,
        'scan_method'        => AttendanceScanMethod::Code,
    ]);

    $component = Livewire::actingAs($user)
        ->test(ScanEntry::class)
        ->set('manualCode', '123456')
        ->call('submitManualCode');

    expect($component->get('scanResult'))->toBe('warning');
    expect(Attendance::where('application_id', $app->id)->whereDate('event_date', today())->count())->toBe(1);
});

// ── Critère : code invalide → toast rouge ────────────────────────────────────

test('invalid 6-digit code returns error status', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('agent_entry');

    Livewire::actingAs($user)
        ->test(ScanEntry::class)
        ->set('manualCode', '99999') // only 5 digits
        ->call('submitManualCode')
        ->assertSet('scanResult', 'error');
});

test('unknown check-in code returns error status', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('agent_entry');

    Livewire::actingAs($user)
        ->test(ScanEntry::class)
        ->set('manualCode', '999999')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'error');
});

// ── Critère : premier scan valide enregistre l'attendance ─────────────────────

test('valid check-in code registers attendance', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('agent_entry');

    $app = Application::factory()->create([
        'status'        => ApplicationStatus::Accepted->value,
        'check_in_code' => 654321,
        'group_label'   => 'G1',
    ]);

    Livewire::actingAs($user)
        ->test(ScanEntry::class)
        ->set('manualCode', '654321')
        ->call('submitManualCode')
        ->assertSet('scanResult', 'success');

    expect(Attendance::where('application_id', $app->id)->whereDate('event_date', today())->count())->toBe(1);
});
