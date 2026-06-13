<?php

namespace App\Filament\Widgets;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class QuotaWidget extends Widget
{
    protected static string $view = 'filament.widgets.quota';

    protected static ?int $sort = 8;

    protected function getViewData(): array
    {
        $quota = 180;

        $enrolledBase = Enrollment::where('enrollments.status', EnrollmentStatus::Enrolled->value)
            ->join('users', 'users.id', '=', 'enrollments.user_id');

        $total = Enrollment::where('status', EnrollmentStatus::Enrolled->value)->count();

        $women = (clone $enrolledBase)->where('users.gender', 'F')->count();
        $womenPct = $quota > 0 ? round($women / $quota * 100) : 0;
        $womenTarget = 50;

        $young = (clone $enrolledBase)->whereNotNull('users.birth_date')
            ->where('users.birth_date', '>=', Carbon::now()->subYears(35)->toDateString())
            ->count();
        $youngPct = $quota > 0 ? round($young / $quota * 100) : 0;
        $youngTarget = 40;

        return compact('total', 'quota', 'women', 'womenPct', 'womenTarget', 'young', 'youngPct', 'youngTarget');
    }
}
