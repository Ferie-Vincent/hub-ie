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
        'description',
        'cover_image',
        'key_figures',
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
        'hero_patron_text',
        'hero_subtitle',
        'dates_cles',
        'minister_name',
        'minister_title',
        'minister_speech',
        'stats_cards',
        'cap_objectifs',
        'cap_resultats',
        'cap_pourquoi',
        'programme_j1',
        'programme_j2',
        'programme_j3',
        'programme_j4',
        'institutions',
        'formats_echange',
        'newsletter_title',
        'newsletter_subtitle',
    ];

    protected function casts(): array
    {
        return [
            'application_opens_at' => 'datetime',
            'application_closes_at' => 'datetime',
            'event_starts_at' => 'datetime',
            'event_ends_at' => 'datetime',
            'launched_at' => 'datetime',
            'key_figures' => 'array',
            'dates_cles' => 'array',
            'minister_speech' => 'array',
            'stats_cards' => 'array',
            'cap_objectifs' => 'array',
            'cap_resultats' => 'array',
            'cap_pourquoi' => 'array',
            'programme_j1' => 'array',
            'programme_j2' => 'array',
            'programme_j3' => 'array',
            'programme_j4' => 'array',
            'institutions' => 'array',
            'formats_echange' => 'array',
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

    public function portfolioPhotos(): HasMany
    {
        return $this->hasMany(PortfolioPhoto::class)->orderBy('sort_order');
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
