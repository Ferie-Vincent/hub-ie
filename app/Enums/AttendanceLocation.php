<?php

namespace App\Enums;

enum AttendanceLocation: string
{
    case Cgeci = 'CGECI';
    case CciCi = 'CCI-CI';
    case Seen = 'SEEN';

    public function label(): string
    {
        return match($this) {
            self::Cgeci => 'CGECI — Plateau',
            self::CciCi => 'CCI-CI — Plateau',
            self::Seen  => 'SEEN Hôtel — Abidjan-Plateau',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Cgeci => 'blue',
            self::CciCi => 'indigo',
            self::Seen  => 'purple',
        };
    }
}
