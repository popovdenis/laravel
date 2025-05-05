<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotValidationTrait;

class EditScheduleTemplate extends EditRecord
{
    use TimeSlotValidationTrait, TimeSlotConverter;

    protected static string $resource = ScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->convertTimeSlotsBeforeSave($data, 'slots');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->convertTimeSlotsBeforeFill($data, 'slots');
    }

    protected function beforeSave(): void
    {
        $this->validateOverlappingSlots($this->data, 'slots');
    }
}
