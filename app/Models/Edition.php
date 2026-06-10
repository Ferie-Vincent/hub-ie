<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'theme',
        'location',
        'application_opens_at',
        'application_closes_at',
        'event_starts_at',
        'event_ends_at',
        'max_participants',
        'quota_women_min_pct',
        'quota_youth_min_pct',
        'quota_youth_max_age',
        'registration_open',
        'is_active',
        'launched_at',
    ];

    protected function casts(): array
    {
        return [
            'application_opens_at' => 'datetime',
            'application_closes_at' => 'datetime',
            'event_starts_at' => 'datetime',
            'event_ends_at' => 'datetime',
            'launched_at' => 'datetime',
            'registration_open' => 'boolean',
            'is_active' => 'boolean',
            'max_participants' => 'integer',
            'quota_women_min_pct' => 'integer',
            'quota_youth_min_pct' => 'integer',
            'quota_youth_max_age' => 'integer',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Static helpers ───────────────────────────────────────────────────────

    public static function current(): ?static
    {
        return static::active()->first();
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // ── Business logic ───────────────────────────────────────────────────────

    /**
     * Activate this edition, deactivate all others.
     * Does NOT send the announcement email — use the Job for that.
     */
    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    public function isRegistrationOpen(): bool
    {
        if (! $this->registration_open) {
            return false;
        }

        $now = now();

        if ($this->application_opens_at && $now->lt($this->application_opens_at)) {
            return false;
        }

        if ($this->application_closes_at && $now->gt($this->application_closes_at)) {
            return false;
        }

        return true;
    }

    public function hasBeenLaunched(): bool
    {
        return $this->launched_at !== null;
    }
}
