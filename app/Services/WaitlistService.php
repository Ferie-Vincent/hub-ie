<?php

namespace App\Services;

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Jobs\GenerateEnrollmentBadge;
use App\Mail\WaitlistPromoted;
use App\Models\AuditLog;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WaitlistService
{
    /**
     * Promeut le premier inscrit en liste d'attente pour ce workshop.
     * Atomique : lockForUpdate sur l'enrollment promu.
     */
    public function promoteNext(Workshop $workshop): ?Enrollment
    {
        return DB::transaction(function () use ($workshop) {
            $next = Enrollment::where('workshop_id', $workshop->id)
                ->where('status', EnrollmentStatus::Waitlisted->value)
                ->orderBy('created_at')
                ->lockForUpdate()
                ->first();

            if (! $next) {
                return null;
            }

            $next->update([
                'status' => EnrollmentStatus::Enrolled,
                'badge_status' => BadgeStatus::Valid,
                'waitlist_offered_at' => now('Africa/Abidjan'),
                'enrolled_at' => now('Africa/Abidjan'),
            ]);

            $workshop->increment('registered_count');

            GenerateEnrollmentBadge::dispatch($next)->afterCommit();
            Mail::to($next->user->email)->queue(new WaitlistPromoted($next));

            AuditLog::create([
                'action' => 'promoted_from_waitlist',
                'subject_type' => Enrollment::class,
                'subject_id' => $next->id,
                'new_values' => ['workshop' => $workshop->title],
            ]);

            return $next;
        });
    }

    /**
     * Promotion manuelle admin depuis Filament.
     */
    public function promoteManually(Enrollment $enrollment, User $admin): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $admin) {
            $workshop = Workshop::lockForUpdate()->findOrFail($enrollment->workshop_id);

            $enrollment->update([
                'status' => EnrollmentStatus::Enrolled,
                'badge_status' => BadgeStatus::Valid,
                'waitlist_offered_at' => now('Africa/Abidjan'),
                'enrolled_at' => now('Africa/Abidjan'),
            ]);

            $workshop->increment('registered_count');

            AuditLog::create([
                'user_id' => $admin->id,
                'action' => 'manually_promoted',
                'subject_type' => Enrollment::class,
                'subject_id' => $enrollment->id,
                'new_values' => ['workshop' => $workshop->title],
            ]);

            GenerateEnrollmentBadge::dispatch($enrollment->fresh())->afterCommit();
            Mail::to($enrollment->user->email)->queue(new WaitlistPromoted($enrollment->fresh()));

            return $enrollment->fresh();
        });
    }
}
