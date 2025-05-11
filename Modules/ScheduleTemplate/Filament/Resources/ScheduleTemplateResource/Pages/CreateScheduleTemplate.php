<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotValidationTrait;

class CreateScheduleTemplate extends CreateRecord
{
    use TimeSlotValidationTrait;

    protected static string $resource = ScheduleTemplateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->convertTimeSlotsBeforeSave($data, 'slots');
    }

    protected function beforeCreate(): void
    {
        $this->validateOverlappingSlots($this->data, 'slots');
    }
}
