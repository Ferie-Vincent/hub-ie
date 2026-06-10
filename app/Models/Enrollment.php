<?php

namespace App\Models;

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'workshop_id',
        'status',
        'badge_status',
        'qr_token',
        'check_in_code',
        'badge_path',
        'waitlist_offered_at',
        'cancellation_token',
        'enrolled_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
            'badge_status' => BadgeStatus::class,
            'waitlist_offered_at' => 'datetime',
            'enrolled_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            EnrollmentStatus::Enrolled->value,
            EnrollmentStatus::Waitlisted->value,
        ]);
    }

    public function scopeEnrolled($query)
    {
        return $query->where('status', EnrollmentStatus::Enrolled->value);
    }

    public function scopeWaitlisted($query)
    {
        return $query->where('status', EnrollmentStatus::Waitlisted->value);
    }

    // Offre expirée si waitlist_offered_at > 24h et toujours en attente
    public function scopeExpiredOffer($query)
    {
        return $query->where('status', EnrollmentStatus::Waitlisted->value)
            ->whereNotNull('waitlist_offered_at')
            ->where('waitlist_offered_at', '<=', now()->subHours(24));
    }

    public function hasBadge(): bool
    {
        return $this->badge_path && Storage::disk('local')->exists($this->badge_path);
    }

    public function isBadgeValid(): bool
    {
        return $this->badge_status === BadgeStatus::Valid;
    }
}
