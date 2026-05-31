<?php

namespace App\Models;

use App\Enums\PartnerTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'logo_white_path',
        'website',
        'tier',
        'display_order',
        'show_in_marquee',
        'show_in_footer',
    ];

    protected function casts(): array
    {
        return [
            'tier' => PartnerTier::class,
            'display_order' => 'integer',
            'show_in_marquee' => 'boolean',
            'show_in_footer' => 'boolean',
        ];
    }

    public function getAcronymAttribute(): string
    {
        $name = $this->name;

        // Short names are their own acronym
        if (mb_strlen($name) <= 8 && preg_match('/^[A-Z0-9\-]+$/i', $name)) {
            return mb_strtoupper($name);
        }

        // Strip parenthetical content
        $cleaned = preg_replace('/\([^)]+\)/', '', $name);

        // Extract first capital letter of each word boundary
        preg_match_all('/(?:^|(?<=[^A-Z]))([A-Z])/', $cleaned, $m);
        $letters = $m[1];

        if (count($letters) >= 2) {
            return implode('', array_slice($letters, 0, 4));
        }

        return mb_strtoupper(mb_substr(trim($cleaned), 0, 3));
    }
}
