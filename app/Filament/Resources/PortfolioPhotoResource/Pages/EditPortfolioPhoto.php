<?php

namespace App\Filament\Resources\PortfolioPhotoResource\Pages;

use App\Filament\Resources\PortfolioPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioPhoto extends EditRecord
{
    protected static string $resource = PortfolioPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
