<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\AuditLog;

class ApplicationObserver
{
    public function updated(Application $application): void
    {
        $dirty = $application->getDirty();

        if (empty($dirty)) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'application.updated',
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_values' => json_encode(array_intersect_key($application->getOriginal(), $dirty)),
            'new_values' => json_encode($dirty),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function created(Application $application): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'application.created',
            'subject_type' => Application::class,
            'subject_id' => $application->id,
            'old_values' => null,
            'new_values' => json_encode($application->getAttributes()),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
