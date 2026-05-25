<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\Widget;

class StatusFunnelWidget extends Widget
{
    protected static string $view = 'filament.widgets.status-funnel';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 2;

    protected function getViewData(): array
    {
        $steps = [
            ApplicationStatus::Received,
            ApplicationStatus::Eligible,
            ApplicationStatus::UnderReview,
            ApplicationStatus::Shortlisted,
            ApplicationStatus::Accepted,
        ];

        $max = Application::where('status', ApplicationStatus::Received->value)->count() ?: 1;

        $rows = [];
        foreach ($steps as $status) {
            $count   = Application::where('status', $status->value)->count();
            $rows[]  = [
                'label' => $status->label(),
                'count' => $count,
                'pct'   => round($count / $max * 100),
                'color' => $this->statusColor($status),
            ];
        }

        return ['rows' => $rows];
    }

    private function statusColor(ApplicationStatus $s): string
    {
        return match($s) {
            ApplicationStatus::Received    => 'hsl(210 70% 55%)',
            ApplicationStatus::Eligible    => 'hsl(180 60% 40%)',
            ApplicationStatus::UnderReview => 'hsl(240 55% 55%)',
            ApplicationStatus::Shortlisted => 'hsl(270 55% 55%)',
            ApplicationStatus::Accepted    => 'hsl(140 50% 38%)',
            default                        => 'hsl(0 0% 60%)',
        };
    }
}
