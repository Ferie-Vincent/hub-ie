<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AgeGroupWidget extends ChartWidget
{
    protected static ?string $heading = 'Tranches d\'âge';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $exclude = [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value];
        $base = Application::whereNotIn('status', $exclude)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->whereNotNull('users.birth_date');

        $ranges = [
            '< 25 ans' => [0,  24],
            '25 – 34 ans' => [25, 34],
            '35 – 44 ans' => [35, 44],
            '45 – 54 ans' => [45, 54],
            '55 ans+' => [55, 99],
        ];

        $labels = [];
        $counts = [];
        $now = Carbon::now();

        foreach ($ranges as $label => [$min, $max]) {
            $labels[] = $label;
            $counts[] = (clone $base)
                ->where('users.birth_date', '<=', $now->copy()->subYears($min)->toDateString())
                ->where('users.birth_date', '>=', $now->copy()->subYears($max + 1)->toDateString())
                ->count();
        }

        return [
            'datasets' => [[
                'label' => 'Candidats',
                'data' => $counts,
                'backgroundColor' => 'hsla(145,65%,40%,0.75)',
                'borderColor' => 'hsl(145 65% 40%)',
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
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
        ];
    }
}
