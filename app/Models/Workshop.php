<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'registered_count',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'themes' => 'array',
            'capacity' => 'integer',
            'registered_count' => 'integer',
            'display_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)
            ->whereIn('status', [EnrollmentStatus::Enrolled->value, EnrollmentStatus::Waitlisted->value]);
    }

    public function scopeAvailable($query)
    {
        return $query->whereColumn('registered_count', '<', 'capacity');
    }

    public function isFull(): bool
    {
        return $this->registered_count >= $this->capacity;
    }

    public function spotsLeft(): int
    {
        return max(0, $this->capacity - $this->registered_count);
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_workshops');
    }

    public function courseFiles(): HasMany
    {
        return $this->hasMany(WorkshopCourseFile::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
