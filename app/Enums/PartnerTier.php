<?php

namespace App\Enums;

enum PartnerTier: string
{
    case Organizer = 'organizer';
    case Strategic = 'strategic';
    case Partner = 'partner';
    case Media = 'media';

    public function label(): string
    {
        return match($this) {
            self::Organizer => 'Organisateur',
            self::Strategic => 'Partenaire stratégique',
            self::Partner   => 'Agence d\'appui',
            self::Media     => 'Médias',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Organizer => 'warning',
            self::Strategic => 'success',
            self::Partner   => 'indigo',
            self::Media     => 'gray',
        };
    }
}
