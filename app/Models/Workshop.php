<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'full_description',
        'objectives',
        'themes',
        'icon_path',
        'capacity',
        'display_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'objectives'   => 'array',
            'themes'       => 'array',
            'capacity'     => 'integer',
            'display_order'=> 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_workshops');
    }
}
