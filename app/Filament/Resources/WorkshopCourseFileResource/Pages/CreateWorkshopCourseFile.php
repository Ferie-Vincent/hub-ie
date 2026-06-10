<?php

namespace App\Filament\Resources\WorkshopCourseFileResource\Pages;

use App\Filament\Resources\WorkshopCourseFileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkshopCourseFile extends CreateRecord
{
    protected static string $resource = WorkshopCourseFileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}
