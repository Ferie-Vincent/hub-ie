<?php

namespace App\Enums;

enum DocumentType: string
{
    case Cv = 'cv';
    case Rccm = 'rccm';
    case IdCard = 'id_card';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Cv     => 'Curriculum vitae',
            self::Rccm   => 'Attestation RCCM',
            self::IdCard => 'Pièce d\'identité',
            self::Other  => 'Autre document',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Cv   => 'success',
            self::Rccm => 'warning',
            default    => 'gray',
        };
    }
}
