<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\ChartWidget;

class CategoryWidget extends ChartWidget
{
    protected static ?string $heading = 'Profils professionnels';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $labels = [];
        $counts = [];

        foreach (ApplicationCategory::cases() as $cat) {
            $count = Application::where('category', $cat->value)
                ->whereNotIn('status', [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value])
                ->count();
            if ($count > 0) {
                $labels[] = $cat->label();
                $counts[] = $count;
            }
        }

        array_multisort($counts, SORT_DESC, $labels);

        return [
            'datasets' => [[
                'axis'            => 'y',
                'label'           => 'Candidats',
                'data'            => $counts,
                'backgroundColor' => 'hsla(25,68%,44%,0.72)',
                'borderColor'     => 'hsl(25 68% 44%)',
                'borderWidth'     => 1,
                'borderRadius'    => 4,
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
            'plugins'   => ['legend' => ['display' => false]],
            'scales'    => ['x' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
        ];
    }
}
