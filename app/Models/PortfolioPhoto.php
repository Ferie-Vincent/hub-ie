<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioPhoto extends Model
{
    protected $fillable = ['edition_id', 'image', 'caption', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }
}
