<?php

namespace App\Filament\Widgets;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class WorkshopChoiceWidget extends ChartWidget
{
    protected static ?string $heading = 'Inscriptions par atelier';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $rows = Enrollment::where('enrollments.status', EnrollmentStatus::Enrolled->value)
            ->join('workshops', 'workshops.id', '=', 'enrollments.workshop_id')
            ->selectRaw('workshops.title, COUNT(*) as total')
            ->groupBy('workshops.id', 'workshops.title')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [[
                'label' => 'Inscrits',
                'data' => $rows->pluck('total')->values()->toArray(),
                'backgroundColor' => [
                    'hsla(145,68%,33%,0.88)',
                    'hsla(145,65%,42%,0.85)',
                    'hsla(162,58%,38%,0.85)',
                    'hsla(128,50%,46%,0.82)',
                ],
                'borderRadius' => 4,
            ]],
            'labels' => $rows->pluck('title')->values()->toArray(),
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
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
        ];
    }
}
