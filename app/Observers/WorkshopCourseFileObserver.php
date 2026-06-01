<?php

namespace App\Observers;

use App\Enums\ApplicationStatus;
use App\Models\User;
use App\Models\WorkshopCourseFile;
use App\Notifications\CourseFileUpdated;
use App\Notifications\NewCourseFileUploaded;
use Illuminate\Support\Facades\Notification;

class WorkshopCourseFileObserver
{
    public function created(WorkshopCourseFile $file): void
    {
        if (! $file->is_published) {
            return;
        }

        $this->notifyAcceptedParticipants($file, new NewCourseFileUploaded($file));
    }

    public function updated(WorkshopCourseFile $file): void
    {
        if ($file->wasChanged('is_published') && $file->is_published) {
            $this->notifyAcceptedParticipants($file, new NewCourseFileUploaded($file));

            return;
        }

        if ($file->wasChanged() && $file->is_published) {
            $this->notifyAcceptedParticipants($file, new CourseFileUpdated($file));
        }
    }

    private function notifyAcceptedParticipants(WorkshopCourseFile $file, object $notification): void
    {
        $users = User::whereHas('applications', function ($q) use ($file) {
            $q->where('status', ApplicationStatus::Accepted->value)
                ->whereHas('workshops', fn ($w) => $w->where('workshops.id', $file->workshop_id));
        })->get();

        if ($users->isEmpty()) {
            return;
        }

        Notification::send($users, $notification);
    }
}
