<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotValidationTrait;

class EditScheduleTemplate extends EditRecord
{
    use TimeSlotValidationTrait;

    protected static string $resource = ScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->validateOverlappingSlots($this->data, 'slots');
    }
}
