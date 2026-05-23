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
            'tier'           => PartnerTier::class,
            'display_order'  => 'integer',
            'show_in_marquee'=> 'boolean',
            'show_in_footer' => 'boolean',
        ];
    }
}
