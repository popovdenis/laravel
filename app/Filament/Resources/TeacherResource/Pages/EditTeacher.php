<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        $this->record->scheduleTimeslots()->delete();

        foreach ($data['timesheet'] ?? [] as $slot) {
            $this->record->scheduleTimeslots()->create([
                'day' => $slot['day'],
                'start' => $slot['start'],
                'end' => $slot['end'],
            ]);
        }
    }
}
