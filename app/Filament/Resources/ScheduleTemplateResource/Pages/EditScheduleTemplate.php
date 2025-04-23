<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
        return $this->convertTimeSlotsBeforeSave($data);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->convertTimeSlotsBeforeFill($data);
    }

    protected function beforeSave(): void
    {
        $this->validateOverlappingSlots($this->data);
    }
}
