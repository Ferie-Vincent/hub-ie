<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $total = Application::whereNotIn('status', [
            ApplicationStatus::Draft->value,
            ApplicationStatus::Withdrawn->value,
        ])->count();

        $delta24h = Application::where('submitted_at', '>=', now()->subDay())->count();

        $accepted = Application::where('status', ApplicationStatus::Accepted->value)->count();
        $quota = 180;

        $today = Carbon::today();
        $present = Attendance::whereDate('event_date', $today)->distinct('application_id')->count('application_id');

        $toEval = Application::whereIn('status', [
            ApplicationStatus::Eligible->value,
            ApplicationStatus::UnderReview->value,
        ])->count();

        $sparkline = $this->submissionsSparkline();

        return [
            Stat::make('Candidatures reçues', $total)
                ->description('+'.$delta24h.' dernières 24h')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($sparkline),

            Stat::make('Auditeurs retenus', $accepted.' / '.$quota)
                ->description(round($accepted / $quota * 100).' % du quota atteint')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Présents aujourd\'hui', $present)
                ->description('Pointage du '.$today->translatedFormat('d M'))
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),

            Stat::make('Dossiers à évaluer', $toEval)
                ->description('Éligibles + en cours')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
        ];
    }

    private function submissionsSparkline(): array
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $data[] = Application::whereDate('submitted_at', $date)->count();
        }

        return $data;
    }
}
