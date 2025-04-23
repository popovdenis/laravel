<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource;
use App\Filament\Resources\TimeSlotValidationTrait;
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
