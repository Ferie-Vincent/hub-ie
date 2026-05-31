<?php

namespace App\Enums;

enum Gender: string
{
    case Female = 'F';
    case Male = 'M';
    case Other = 'X';

    public function label(): string
    {
        return match ($this) {
            self::Female => 'Femme',
            self::Male => 'Homme',
            self::Other => 'Préfère ne pas préciser',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Female => 'pink',
            self::Male => 'blue',
            self::Other => 'gray',
        };
    }
}
