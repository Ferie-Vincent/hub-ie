<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Jobs\GenerateBadgePdf;
use App\Mail\ApplicationAccepted;
use App\Mail\ApplicationEligible;
use App\Mail\ApplicationIncomplete;
use App\Mail\ApplicationReceived;
use App\Mail\ApplicationRejected;
use App\Mail\ApplicationShortlisted;
use App\Mail\ApplicationWaitlisted;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;

class ApplicationStatusService
{
    /**
     * Valid transitions: [from => [allowed to, ...]]
     */
    private const TRANSITIONS = [
        ApplicationStatus::Draft->value => [
            ApplicationStatus::Received->value,
            ApplicationStatus::Withdrawn->value,
        ],
        ApplicationStatus::Received->value => [
            ApplicationStatus::Eligible->value,
            ApplicationStatus::Incomplete->value,
            ApplicationStatus::Withdrawn->value,
        ],
        ApplicationStatus::Incomplete->value => [
            ApplicationStatus::Received->value,
            ApplicationStatus::Withdrawn->value,
        ],
        ApplicationStatus::Eligible->value => [
            ApplicationStatus::UnderReview->value,
            ApplicationStatus::Incomplete->value,
            ApplicationStatus::Withdrawn->value,
        ],
        ApplicationStatus::UnderReview->value => [
            ApplicationStatus::Shortlisted->value,
            ApplicationStatus::Rejected->value,
            ApplicationStatus::Withdrawn->value,
        ],
        ApplicationStatus::Shortlisted->value => [
            ApplicationStatus::Accepted->value,
            ApplicationStatus::Waitlisted->value,
            ApplicationStatus::Rejected->value,
        ],
        ApplicationStatus::Waitlisted->value => [
            ApplicationStatus::Accepted->value,
            ApplicationStatus::Rejected->value,
        ],
        ApplicationStatus::Accepted->value  => [],
        ApplicationStatus::Rejected->value  => [],
        ApplicationStatus::Withdrawn->value => [],
    ];

    public function transition(Application $app, ApplicationStatus $to, ?string $notes = null): void
    {
        $from = $app->status->value;

        if (! in_array($to->value, self::TRANSITIONS[$from] ?? [], true)) {
            throw new \DomainException(
                "Transition interdite : {$from} → {$to->value} (candidature #{$app->id})"
            );
        }

        $updates = [
            'status'      => $to,
            'admin_notes' => $notes ?? $app->admin_notes,
        ];

        if ($to === ApplicationStatus::Received && $app->submitted_at === null) {
            $updates['submitted_at'] = now();
        }

        if ($to === ApplicationStatus::Accepted) {
            $this->assignQrAndGroup($app, $updates);
            $updates['accepted_at'] = now();
        }

        $app->update($updates);

        if ($to === ApplicationStatus::Accepted) {
            GenerateBadgePdf::dispatch($app);
        }

        $this->sendNotificationEmail($app, $to);
    }

    public function canTransition(Application $app, ApplicationStatus $to): bool
    {
        return in_array($to->value, self::TRANSITIONS[$app->status->value] ?? [], true);
    }

    private function assignQrAndGroup(Application $app, array &$updates): void
    {
        $qrService    = app(QrCodeService::class);
        $groupService = app(GroupAssignmentService::class);

        if (! $app->qr_token) {
            $token              = $qrService->generateUniqueQrToken();
            $updates['qr_token'] = $token;
        }

        if (! $app->check_in_code) {
            $updates['check_in_code'] = $qrService->generateUniqueCheckInCode();
        }

        if (! $app->group_label) {
            $updates['group_label'] = $groupService->assign($app);
        }
    }

    private function sendNotificationEmail(Application $app, ApplicationStatus $to): void
    {
        try {
            match ($to) {
                ApplicationStatus::Received    => Mail::to($app->user->email)->queue(new ApplicationReceived($app)),
                ApplicationStatus::Eligible    => Mail::to($app->user->email)->queue(new ApplicationEligible($app)),
                ApplicationStatus::Incomplete  => Mail::to($app->user->email)->queue(new ApplicationIncomplete($app)),
                ApplicationStatus::Shortlisted => Mail::to($app->user->email)->queue(new ApplicationShortlisted($app)),
                ApplicationStatus::Accepted    => null, // handled in GenerateBadgePdf job
                ApplicationStatus::Waitlisted  => Mail::to($app->user->email)->queue(new ApplicationWaitlisted($app)),
                ApplicationStatus::Rejected    => Mail::to($app->user->email)->queue(new ApplicationRejected($app)),
                default                        => null,
            };
        } catch (\Throwable) {
            // Mail failures must not block status transitions
        }
    }
}
