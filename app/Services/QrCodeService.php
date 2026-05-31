<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class QrCodeService
{
    public function generateUniqueQrToken(): string
    {
        do {
            $token = bin2hex(random_bytes(24)); // 48-char hex
        } while (Application::where('qr_token', $token)->exists());

        return $token;
    }

    public function generateSignedUrl(string $token): string
    {
        $expiry = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            '2026-06-25 23:59:59',
            'Africa/Abidjan'
        );

        return URL::temporarySignedRoute('scan.qr', $expiry, ['token' => $token]);
    }

    public function generateUniqueCheckInCode(): int
    {
        do {
            $code = random_int(100000, 999999);
        } while (Application::where('check_in_code', $code)->exists());

        return $code;
    }
}
