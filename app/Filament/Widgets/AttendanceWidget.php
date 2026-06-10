<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendanceWidget extends ChartWidget
{
    protected static ?string $heading = 'Pointage par jour';

    protected static ?int $sort = 9;

    protected static ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $days = ['22 juin', '23 juin', '24 juin', '25 juin'];
        $dates = ['2026-06-22', '2026-06-23', '2026-06-24', '2026-06-25'];
        $counts = [];

        foreach ($dates as $date) {
            $counts[] = Attendance::whereDate('event_date', $date)
                ->distinct('enrollment_id')
                ->count('enrollment_id');
        }

        return [
            'datasets' => [[
                'label' => 'Présents',
                'data' => $counts,
                'backgroundColor' => 'hsla(140,50%,38%,0.80)',
                'borderColor' => 'hsl(140 50% 38%)',
                'borderWidth' => 1,
                'borderRadius' => 6,
            ]],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'max' => 180, 'ticks' => ['stepSize' => 30]]],
        ];
    }
}
