<?php

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use App\Services\QrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

// ── QR token uniqueness ──────────────────────────────────────────────────────

test('generateUniqueQrToken returns 48-char hex string', function () {
    $service = new QrCodeService;
    $token = $service->generateUniqueQrToken();

    expect($token)->toHaveLength(48)
        ->and(ctype_xdigit($token))->toBeTrue();
});

test('generateUniqueQrToken never collides across 100 enrollments', function () {
    $service = new QrCodeService;
    $user = User::factory()->create();
    $workshop = Workshop::factory()->create();
    $tokens = [];

    for ($i = 0; $i < 100; $i++) {
        $token = $service->generateUniqueQrToken();
        Enrollment::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => 'enrolled',
            'badge_status' => 'valid',
            'qr_token' => $token,
            'cancellation_token' => Str::random(64).'_'.$i,
            'enrolled_at' => now(),
        ]);
        $tokens[] = $token;
    }

    expect(array_unique($tokens))->toHaveCount(100);
});

// ── Check-in code ─────────────────────────────────────────────────────────────

test('generateUniqueCheckInCode returns 6-digit integer', function () {
    $service = new QrCodeService;
    $code = $service->generateUniqueCheckInCode();

    expect($code)->toBeInt()
        ->and($code)->toBeGreaterThanOrEqual(100000)
        ->and($code)->toBeLessThanOrEqual(999999);
});

test('generateUniqueCheckInCode never starts with zero', function () {
    $service = new QrCodeService;
    $code = $service->generateUniqueCheckInCode();

    expect((string) $code)->not->toStartWith('0');
});
