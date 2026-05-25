<?php

use App\Models\Application;
use App\Services\QrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ── QR token uniqueness ──────────────────────────────────────────────────────

test('generateUniqueQrToken returns 48-char hex string', function () {
    $service = new QrCodeService();
    $token   = $service->generateUniqueQrToken();

    expect($token)->toHaveLength(48)
        ->and(ctype_xdigit($token))->toBeTrue();
});

test('generateUniqueQrToken never collides across 100 applications', function () {
    $service = new QrCodeService();
    $tokens  = [];

    for ($i = 0; $i < 100; $i++) {
        $token = $service->generateUniqueQrToken();
        Application::factory()->create(['qr_token' => $token]);
        $tokens[] = $token;
    }

    expect(array_unique($tokens))->toHaveCount(100);
});

// ── Check-in code ─────────────────────────────────────────────────────────────

test('generateUniqueCheckInCode returns 6-digit integer', function () {
    $service = new QrCodeService();
    $code    = $service->generateUniqueCheckInCode();

    expect($code)->toBeInt()
        ->and($code)->toBeGreaterThanOrEqual(100000)
        ->and($code)->toBeLessThanOrEqual(999999);
});

test('generateUniqueCheckInCode never starts with zero', function () {
    $service = new QrCodeService();
    $code    = $service->generateUniqueCheckInCode();

    expect((string) $code)->not->toStartWith('0');
});

// ── Signed URL ───────────────────────────────────────────────────────────────

test('generateSignedUrl returns a valid URL containing the token', function () {
    $service = new QrCodeService();
    $token   = $service->generateUniqueQrToken();
    $url     = $service->generateSignedUrl($token);

    expect($url)->toContain($token)
        ->and(filter_var($url, FILTER_VALIDATE_URL))->not->toBeFalse();
});
