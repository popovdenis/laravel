<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScheduleTemplate extends CreateRecord
{
    use TimeSlotValidationTrait, TimeSlotConverter;

    protected static string $resource = ScheduleTemplateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->convertTimeSlotsBeforeSave($data);
    }

    protected function beforeCreate(): void
    {
        $this->validateOverlappingSlots($this->data);
    }
}
