<?php

namespace App\Notifications;

use App\Models\WorkshopCourseFile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CourseFileUpdated extends Notification
{
    use Queueable;

    public function __construct(public readonly WorkshopCourseFile $file) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'course_file_updated',
            'action' => 'updated',
            'file_id' => $this->file->id,
            'file_title' => $this->file->title,
            'workshop_id' => $this->file->workshop_id,
            'workshop_title' => $this->file->workshop->title,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
