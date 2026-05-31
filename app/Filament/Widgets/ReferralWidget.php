<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\ChartWidget;

class ReferralWidget extends ChartWidget
{
    protected static ?string $heading = 'Sources de connaissance';

    protected static ?int $sort = 11;

    protected function getData(): array
    {
        $data = Application::whereNotIn('status', [
            ApplicationStatus::Draft->value,
            ApplicationStatus::Withdrawn->value,
        ])
            ->whereNotNull('referral_source')
            ->selectRaw('referral_source, COUNT(*) as total')
            ->groupBy('referral_source')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'referral_source')
            ->toArray();

        $labels = array_map(fn ($k) => str_replace('_', ' ', ucfirst($k)), array_keys($data));

        return [
            'datasets' => [[
                'label' => 'Candidats',
                'data' => array_values($data),
                'backgroundColor' => 'hsla(25,68%,44%,0.75)',
                'borderColor' => 'hsl(25 68% 44%)',
                'borderWidth' => 1,
                'borderRadius' => 4,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['x' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
        ];
    }
}
