<?php

namespace App\Services;

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CancellationService
{
    // Date de début de l'événement — timezone Africa/Abidjan (UTC+0)
    private const EVENT_START = '2026-06-22 00:00:00';

    private const TIMEZONE = 'Africa/Abidjan';

    /**
     * Vérifie si la désinscription est encore possible (avant J-1).
     */
    public function canCancel(): bool
    {
        $deadline = Carbon::parse(self::EVENT_START, self::TIMEZONE)->subDay();

        return now(self::TIMEZONE)->lt($deadline);
    }

    /**
     * Désinscrit un participant via son token signé.
     * Invalide le badge, libère la place, déclenche la promotion waitlist.
     */
    public function cancelByToken(string $token): Enrollment
    {
        $enrollment = Enrollment::where('cancellation_token', $token)
            ->where('status', EnrollmentStatus::Enrolled->value)
            ->firstOrFail();

        if (! $this->canCancel()) {
            throw new \RuntimeException('Le délai de désinscription est dépassé (J-1).');
        }

        DB::transaction(function () use ($enrollment) {
            $enrollment->update([
                'status' => EnrollmentStatus::Cancelled,
                'badge_status' => BadgeStatus::Invalid,
                'cancelled_at' => now(self::TIMEZONE),
            ]);

            $enrollment->workshop->decrement('registered_count');

            // Déclenche la promotion du premier waitlisté
            app(WaitlistService::class)->promoteNext($enrollment->workshop);
        });

        return $enrollment->fresh();
    }

    public function deadlineLabel(): string
    {
        return Carbon::parse(self::EVENT_START, self::TIMEZONE)
            ->subDay()
            ->translatedFormat('d F Y');
    }
}
