<?php

namespace App\Filament\Resources\WorkshopCourseFileResource\Pages;

use App\Filament\Resources\WorkshopCourseFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkshopCourseFiles extends ListRecords
{
    protected static string $resource = WorkshopCourseFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
