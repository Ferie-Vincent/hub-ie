<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class QuotaWidget extends Widget
{
    protected static string $view = 'filament.widgets.quota';

    protected static ?int $sort = 8;

    protected function getViewData(): array
    {
        $accepted = Application::where('status', ApplicationStatus::Accepted->value);

        $total = (clone $accepted)->count();
        $quota = 180;

        $women = (clone $accepted)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->where('users.gender', 'F')
            ->count();
        $womenPct = $quota > 0 ? round($women / $quota * 100) : 0;
        $womenTarget = 50;

        $young = Application::where('status', ApplicationStatus::Accepted->value)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->where('users.birth_date', '>=', Carbon::now()->subYears(35)->toDateString())
            ->count();
        $youngPct = $quota > 0 ? round($young / $quota * 100) : 0;
        $youngTarget = 40;

        return compact('total', 'quota', 'women', 'womenPct', 'womenTarget', 'young', 'youngPct', 'youngTarget');
    }
}
