<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Widgets\Widget;

class GeographyWidget extends Widget
{
    protected static string $view = 'filament.widgets.geography';

    protected static ?int $sort = 10;

    protected function getViewData(): array
    {
        $exclude = [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value];

        $cities = Application::whereNotIn('status', $exclude)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->whereNotNull('users.city')
            ->selectRaw('users.city as city, COUNT(*) as total')
            ->groupBy('users.city')
            ->orderByDesc('total')
            ->limit(15)
            ->pluck('total', 'city')
            ->toArray();

        $max = max($cities ?: [1]);

        return ['cities' => $cities, 'max' => $max];
    }
}
