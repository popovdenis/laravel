<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\ScheduleTimeslot;
use Filament\Actions;
use App\Filament\Resources\UserResource\Pages\EditUser;

class EditTeacher extends EditUser
{
    protected static string $resource = TeacherResource::class;
    protected static ?string $title = 'Edit Teacher';
    protected static ?string $breadcrumb = 'Edit Teacher';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->form->fill(array_merge(
            $this->form->getState(),
            [
                'timesheet' => ScheduleTimeslot::query()
                    ->where('user_id', $record)
                    ->get(['day', 'start', 'end'])
                    ->toArray(),
            ]
        ));
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->id]);
    }
}
