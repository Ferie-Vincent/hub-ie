<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Workshop;
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

        $enrolled = Enrollment::where('status', EnrollmentStatus::Enrolled->value)->count();

        $totalCapacity = Workshop::where('is_published', true)->sum('capacity');
        $spotsLeft = max(0, $totalCapacity - $enrolled);

        $today = Carbon::today();
        $present = Attendance::whereDate('event_date', $today)->distinct('enrollment_id')->count('enrollment_id');

        $sparkline = $this->submissionsSparkline();

        return [
            Stat::make('Candidatures reçues', $total)
                ->description('+'.$delta24h.' dernières 24h')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($sparkline),

            Stat::make('Inscrits en ateliers', $enrolled)
                ->description($totalCapacity > 0 ? round($enrolled / $totalCapacity * 100).'% du quota rempli' : '—')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Présents aujourd\'hui', $present)
                ->description('Pointage du '.$today->translatedFormat('d M'))
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),

            Stat::make('Places disponibles', $spotsLeft)
                ->description('Sur '.$totalCapacity.' places totales')
                ->descriptionIcon('heroicon-m-ticket')
                ->color($spotsLeft < 20 ? 'danger' : 'warning'),
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
