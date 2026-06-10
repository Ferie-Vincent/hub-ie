<?php

namespace App\Services;

use App\Models\Enrollment;

class QrCodeService
{
    public function generateUniqueQrToken(): string
    {
        do {
            $token = bin2hex(random_bytes(24)); // 48-char hex
        } while (Enrollment::where('qr_token', $token)->exists());

        return $token;
    }

    public function generateUniqueCheckInCode(): int
    {
        do {
            $code = random_int(100000, 999999);
        } while (Enrollment::where('check_in_code', $code)->exists());

        return $code;
    }
}
