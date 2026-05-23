<?php

namespace App\Enums;

enum ProgramTag: string
{
    case Accueil = 'accueil';
    case Pleniere = 'pleniere';
    case Atelier = 'atelier';
    case Pause = 'pause';
    case B2b = 'b2b';
    case Presse = 'presse';

    public function label(): string
    {
        return match($this) {
            self::Accueil  => 'Accueil',
            self::Pleniere => 'Plénière',
            self::Atelier  => 'Atelier',
            self::Pause    => 'Pause',
            self::B2b      => 'B2B',
            self::Presse   => 'Presse',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Accueil  => 'warning',
            self::Pleniere => 'success',
            self::Atelier  => 'success',
            self::Pause    => 'gray',
            self::B2b      => 'purple',
            self::Presse   => 'orange',
        };
    }
}
