<?php

namespace App\Filament\Resources\WorkshopCourseFileResource\Pages;

use App\Filament\Resources\WorkshopCourseFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkshopCourseFile extends EditRecord
{
    protected static string $resource = WorkshopCourseFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
