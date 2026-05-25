<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Mail\ApplicationReceived;
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

        $app->update([
            'status'      => $to,
            'admin_notes' => $notes ?? $app->admin_notes,
        ]);

        $this->sendNotificationEmail($app, $to);
    }

    public function canTransition(Application $app, ApplicationStatus $to): bool
    {
        return in_array($to->value, self::TRANSITIONS[$app->status->value] ?? [], true);
    }

    private function sendNotificationEmail(Application $app, ApplicationStatus $to): void
    {
        try {
            match ($to) {
                ApplicationStatus::Received => Mail::to($app->user->email)
                    ->queue(new ApplicationReceived($app)),
                default => null,
            };
        } catch (\Throwable) {
            // Mail failures must not block status transitions
        }
    }
}
