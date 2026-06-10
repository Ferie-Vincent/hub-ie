<?php

namespace App\Enums;

enum BadgeStatus: string
{
    case Valid = 'valid';
    case Invalid = 'invalid';

    public function label(): string
    {
        return match ($this) {
            self::Valid => 'Valide',
            self::Invalid => 'Invalide',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Valid => 'success',
            self::Invalid => 'danger',
        };
    }
}
