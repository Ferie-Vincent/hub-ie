<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\ChartWidget;

class GenderWidget extends ChartWidget
{
    protected static ?string $heading = 'Répartition par genre';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $exclude = [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value];

        $base = Application::whereNotIn('status', $exclude)->join('users', 'users.id', '=', 'applications.user_id');

        $f = (clone $base)->where('users.gender', 'F')->count();
        $m = (clone $base)->where('users.gender', 'M')->count();
        $x = (clone $base)->where(fn ($q) => $q->where('users.gender', 'X')->orWhereNull('users.gender'))->count();

        return [
            'datasets' => [[
                'data' => [$f, $m, $x],
                'backgroundColor' => ['hsl(330 60% 60%)', 'hsl(210 70% 55%)', 'hsl(0 0% 70%)'],
                'hoverOffset' => 6,
            ]],
            'labels' => ['Femmes', 'Hommes', 'Autre / NR'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom']],
            'cutout' => '65%',
        ];
    }
}
