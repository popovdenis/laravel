<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource\Pages\TimeSlotConverter;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\TimeSlotValidationTrait;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\ScheduleTimeslot;
use Filament\Actions;

class EditTeacher extends EditUser
{
    use TimeSlotValidationTrait, TimeSlotConverter;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->convertTimeSlotsBeforeSave($data, 'timesheet');

        $this->record->scheduleTimeslots()->delete();

        foreach ($data['timesheet'] ?? [] as $slot) {
            $this->record->scheduleTimeslots()->create([
                'day' => $slot['day'],
                'start' => $slot['start'],
                'end' => $slot['end'],
            ]);
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        $this->validateOverlappingSlots($this->data, 'timesheet');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $grouped = \App\Models\ScheduleTimeslot::query()
            ->where('user_id', $data['id'])
            ->get()
            ->groupBy('day')
            ->mapWithKeys(fn ($slots, $day) => ["{$day}_timesheet" => $slots->map(fn ($slot) => [
                'start' => $slot->start,
                'end'   => $slot->end,
            ])->values()->all()]);

        return array_merge($data, $grouped->toArray());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->id]);
    }
}
