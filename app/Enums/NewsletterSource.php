<?php

namespace App\Enums;

enum NewsletterSource: string
{
    case Hero = 'hero';
    case Footer = 'footer';
    case News = 'news';

    public function label(): string
    {
        return match ($this) {
            self::Hero => 'Hero (accueil)',
            self::Footer => 'Pied de page',
            self::News => 'Actualités',
        };
    }

    public function color(): string
    {
        return 'gray';
    }
}
