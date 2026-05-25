<?php

namespace App\Filament\Pages;

use App\Enums\ApplicationStatus;
use App\Exports\ApplicationsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportCenter extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int    $navigationSort  = 50;
    protected static ?string $title           = 'Centre d\'export';
    protected static string  $view            = 'filament.pages.export-center';

    public function exportAll(): BinaryFileResponse
    {
        return Excel::download(new ApplicationsExport(), 'candidatures-' . now()->format('Ymd-Hi') . '.xlsx');
    }

    public function exportAccepted(): BinaryFileResponse
    {
        return Excel::download(
            new ApplicationsExport(statuses: [ApplicationStatus::Accepted]),
            'candidatures-retenues-' . now()->format('Ymd-Hi') . '.xlsx'
        );
    }

    public function exportWaitlisted(): BinaryFileResponse
    {
        return Excel::download(
            new ApplicationsExport(statuses: [ApplicationStatus::Waitlisted]),
            'liste-attente-' . now()->format('Ymd-Hi') . '.xlsx'
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_all')
                ->label('Toutes les candidatures')
                ->icon('heroicon-m-table-cells')
                ->color('gray')
                ->action('exportAll'),

            Action::make('export_accepted')
                ->label('Retenues uniquement')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->action('exportAccepted'),

            Action::make('export_waitlisted')
                ->label('Liste d\'attente')
                ->icon('heroicon-m-clock')
                ->color('warning')
                ->action('exportWaitlisted'),
        ];
    }
}
