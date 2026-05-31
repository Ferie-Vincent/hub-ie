<?php

namespace App\Enums;

enum AttendanceScanMethod: string
{
    case Qr = 'qr';
    case Code = 'code';

    public function label(): string
    {
        return match ($this) {
            self::Qr => 'QR Code',
            self::Code => 'Code 6 chiffres',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Qr => 'success',
            self::Code => 'blue',
        };
    }
}
