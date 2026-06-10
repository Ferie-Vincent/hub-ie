<?php

namespace App\Services;

use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Jobs\GenerateEnrollmentBadge;
use App\Mail\EnrollmentConfirmed;
use App\Models\AuditLog;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EnrollmentService
{
    public function __construct(private readonly QrCodeService $qrCodeService) {}

    /**
     * Inscrit un utilisateur à un workshop.
     * Retourne l'Enrollment créé (status: enrolled ou waitlisted).
     * Lève une \RuntimeException si l'utilisateur a déjà une inscription active.
     */
    public function enroll(User $user, Workshop $workshop): Enrollment
    {
        return DB::transaction(function () use ($user, $workshop) {
            // Verrou exclusif sur le workshop pour éviter la race condition
            $workshop = Workshop::lockForUpdate()->findOrFail($workshop->id);

            // Un seul enrollment actif par utilisateur (toutes sessions confondues)
            $existing = Enrollment::where('user_id', $user->id)
                ->whereIn('status', [EnrollmentStatus::Enrolled->value, EnrollmentStatus::Waitlisted->value])
                ->first();

            if ($existing) {
                throw new \RuntimeException('Vous êtes déjà inscrit(e) à une session.');
            }

            if ($workshop->isFull()) {
                return $this->addToWaitlist($user, $workshop);
            }

            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'workshop_id' => $workshop->id,
                'status' => EnrollmentStatus::Enrolled,
                'badge_status' => BadgeStatus::Valid,
                'qr_token' => $this->qrCodeService->generateUniqueQrToken(),
                'check_in_code' => $this->qrCodeService->generateUniqueCheckInCode(),
                'enrolled_at' => now('Africa/Abidjan'),
                'cancellation_token' => Str::random(64),
            ]);

            $workshop->increment('registered_count');

            GenerateEnrollmentBadge::dispatch($enrollment)->afterCommit();
            Mail::to($user->email)->queue(new EnrollmentConfirmed($enrollment));

            return $enrollment;
        });
    }

    private function addToWaitlist(User $user, Workshop $workshop): Enrollment
    {
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => EnrollmentStatus::Waitlisted,
            'badge_status' => null,
            'cancellation_token' => Str::random(64),
        ]);

        // Notifie l'admin via Filament Notification (géré dans WaitlistService)
        return $enrollment;
    }

    /**
     * Déplace un inscrit vers un autre workshop (action admin).
     * Atomique : désinscrit de A + inscrit en B dans la même transaction.
     */
    public function moveToWorkshop(Enrollment $enrollment, Workshop $target, User $admin): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $target, $admin) {
            $source = Workshop::lockForUpdate()->findOrFail($enrollment->workshop_id);
            $target = Workshop::lockForUpdate()->findOrFail($target->id);

            // Invalider badge existant
            if ($enrollment->badge_path) {
                app(BadgePdfService::class)->purgeOrphan($enrollment->badge_path);
            }

            // Décrémenter source si était enrolled
            if ($enrollment->status === EnrollmentStatus::Enrolled) {
                $source->decrement('registered_count');
            }

            // Mettre à jour l'enrollment avec la nouvelle session
            $enrollment->update([
                'workshop_id' => $target->id,
                'badge_status' => BadgeStatus::Valid,
                'badge_path' => null,
                'qr_token' => $enrollment->qr_token ?? $this->qrCodeService->generateUniqueQrToken(),
                'check_in_code' => $enrollment->check_in_code ?? $this->qrCodeService->generateUniqueCheckInCode(),
                'status' => EnrollmentStatus::Enrolled,
                'enrolled_at' => now('Africa/Abidjan'),
            ]);

            $target->increment('registered_count');

            AuditLog::create([
                'user_id' => $admin->id,
                'action' => 'moved_to_workshop',
                'subject_type' => Enrollment::class,
                'subject_id' => $enrollment->id,
                'new_values' => ['from' => $source->title, 'to' => $target->title],
            ]);

            GenerateEnrollmentBadge::dispatch($enrollment->fresh())->afterCommit();

            return $enrollment->fresh();
        });
    }

    /**
     * Force-inscription admin hors quota.
     */
    public function forceEnroll(User $user, Workshop $workshop, User $admin): Enrollment
    {
        return DB::transaction(function () use ($user, $workshop, $admin) {
            $existing = Enrollment::where('user_id', $user->id)
                ->whereIn('status', [EnrollmentStatus::Enrolled->value, EnrollmentStatus::Waitlisted->value])
                ->first();

            if ($existing) {
                throw new \RuntimeException('Cet utilisateur est déjà inscrit à une session.');
            }

            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'workshop_id' => $workshop->id,
                'status' => EnrollmentStatus::Enrolled,
                'badge_status' => BadgeStatus::Valid,
                'qr_token' => $this->qrCodeService->generateUniqueQrToken(),
                'check_in_code' => $this->qrCodeService->generateUniqueCheckInCode(),
                'enrolled_at' => now('Africa/Abidjan'),
                'cancellation_token' => Str::random(64),
            ]);

            $workshop->increment('registered_count');

            AuditLog::create([
                'user_id' => $admin->id,
                'action' => 'force_enrolled',
                'subject_type' => Enrollment::class,
                'subject_id' => $enrollment->id,
                'new_values' => ['workshop' => $workshop->title],
            ]);

            GenerateEnrollmentBadge::dispatch($enrollment)->afterCommit();
            Mail::to($user->email)->queue(new EnrollmentConfirmed($enrollment));

            return $enrollment;
        });
    }
}
