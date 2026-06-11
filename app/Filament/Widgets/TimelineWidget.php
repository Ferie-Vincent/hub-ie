<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TimelineWidget extends ChartWidget
{
    protected static ?string $heading = 'Candidatures — 30 derniers jours (cumulé)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $labels = [];
        $values = [];
        $cumul = 0;

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Application::whereDate('submitted_at', $date)->count();
            $cumul += $count;
            $labels[] = $date->format('d/m');
            $values[] = $cumul;
        }

        return [
            'datasets' => [[
                'label' => 'Candidatures cumulées',
                'data' => $values,
                'borderColor' => 'hsl(145 65% 40%)',
                'backgroundColor' => 'hsla(145,65%,40%,0.12)',
                'fill' => true,
                'tension' => 0.4,
                'pointRadius' => 2,
                'pointHoverRadius' => 5,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
        ];
    }
}
