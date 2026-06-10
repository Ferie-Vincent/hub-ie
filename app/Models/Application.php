<?php

namespace App\Models;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'edition_id',
        'reference_code',
        'user_id',
        'status',
        'current_step',
        'category',
        'organization_name',
        'organization_type',
        'position',
        'sector',
        'experience_years',
        'rccm_number',
        'website',
        'professional_email',
        'professional_phone',
        'motivation',
        'chosen_workshops',
        'expectations',
        'referral_source',
        'is_first_participation',
        'rgpd_consent',
        'communication_consent',
        'submitted_at',
        'submission_ip',
        'submission_user_agent',
        'average_score',
        'evaluations_count',
        'group_label',
        'qr_token',
        'check_in_code',
        'badge_path',
        'accepted_at',
        'notified_at',
        'admin_notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'category' => ApplicationCategory::class,
            'chosen_workshops' => 'array',
            'is_first_participation' => 'boolean',
            'rgpd_consent' => 'boolean',
            'communication_consent' => 'boolean',
            'submitted_at' => 'datetime',
            'accepted_at' => 'datetime',
            'notified_at' => 'datetime',
            'average_score' => 'decimal:2',
            'current_step' => 'integer',
            'experience_years' => 'integer',
            'evaluations_count' => 'integer',
        ];
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForEdition(Builder $query, ?Edition $edition = null): Builder
    {
        $edition ??= Edition::current();

        return $edition ? $query->where('edition_id', $edition->id) : $query;
    }

    public function scopeArchived(Builder $query): Builder
    {
        $current = Edition::current();

        return $current
            ? $query->where('edition_id', '!=', $current->id)
            : $query->whereNotNull('edition_id');
    }

    public function workshops(): BelongsToMany
    {
        return $this->belongsToMany(Workshop::class, 'application_workshops');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getQrSignedUrlAttribute(): ?string
    {
        if (! $this->qr_token) {
            return null;
        }

        return app(QrCodeService::class)->generateSignedUrl($this->qr_token);
    }

    public function isAccepted(): bool
    {
        return $this->status === ApplicationStatus::Accepted;
    }

    public function hasBeenSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }
}
