<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Enrolled = 'enrolled';
    case Waitlisted = 'waitlisted';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Enrolled => 'Inscrit',
            self::Waitlisted => 'En attente',
            self::Cancelled => 'Annulé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Enrolled => 'success',
            self::Waitlisted => 'warning',
            self::Cancelled => 'danger',
        };
    }

    public function isActive(): bool
    {
        return $this !== self::Cancelled;
    }
}
