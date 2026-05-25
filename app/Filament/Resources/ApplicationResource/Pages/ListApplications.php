<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Exports\ApplicationsExport;
use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Exporter Excel')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('gray')
                ->action(fn() => Excel::download(
                    new ApplicationsExport(),
                    'candidatures-' . now()->format('Y-m-d') . '.xlsx'
                )),
        ];
    }
}
