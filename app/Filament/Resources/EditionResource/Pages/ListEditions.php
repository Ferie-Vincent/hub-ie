<?php

namespace App\Filament\Resources\EditionResource\Pages;

use App\Filament\Resources\EditionResource;
use App\Models\Edition;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEditions extends ListRecords
{
    protected static string $resource = EditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvelle édition'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getSubheading(): ?string
    {
        $active = Edition::current();

        if (! $active) {
            return 'Aucune édition active pour le moment.';
        }

        return "Édition active : {$active->year} — {$active->title}";
    }
}
