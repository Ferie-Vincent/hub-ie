<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\ChartWidget;

class WorkshopChoiceWidget extends ChartWidget
{
    protected static ?string $heading = 'Choix d\'ateliers';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $slugs = [
            'zlecaf-cedeao' => 'ZLECAf & CEDEAO',
            'financement-garanties' => 'Financement & garanties',
            'commerce-electronique' => 'Commerce électronique',
            'conformite-qualite' => 'Conformité & qualité',
        ];

        $counts = array_fill_keys(array_keys($slugs), 0);

        Application::whereNotIn('status', [
            ApplicationStatus::Draft->value,
            ApplicationStatus::Withdrawn->value,
        ])->whereNotNull('chosen_workshops')->each(function ($app) use (&$counts) {
            foreach ((array) $app->chosen_workshops as $slug) {
                if (isset($counts[$slug])) {
                    $counts[$slug]++;
                }
            }
        });

        return [
            'datasets' => [[
                'label' => 'Inscriptions',
                'data' => array_values($counts),
                'backgroundColor' => [
                    'hsla(145,68%,33%,0.88)',
                    'hsla(145,65%,42%,0.85)',
                    'hsla(162,58%,38%,0.85)',
                    'hsla(128,50%,46%,0.82)',
                ],
                'borderRadius' => 4,
            ]],
            'labels' => array_values($slugs),
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
