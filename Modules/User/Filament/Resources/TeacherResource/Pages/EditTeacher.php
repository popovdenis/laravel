<?php

namespace Modules\User\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotValidationTrait;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;
use Modules\User\Filament\Resources\TeacherResource;
use Modules\User\Filament\Resources\UserResource\Pages\EditUser;

class EditTeacher extends EditUser
{
    use TimeSlotValidationTrait;

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
                    ->get(['day_of_week', 'start_time', 'end_time'])
                    ->toArray(),
            ]
        ));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->record->scheduleTimeslots()->delete();

        collect(
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
        )->each(function ($day) use ($data) {
            $slots = $data[$day . '_timesheet'] ?? null;
            if ($slots) {
                foreach ($slots as $slot) {
                    $this->record->scheduleTimeslots()->create([
                        'day_of_week' => $day,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                    ]);
                }
            }
        });

        return $data;
    }

    protected function beforeSave(): void
    {
        $this->validateOverlappingSlots($this->data, 'timesheet');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $grouped = \Modules\ScheduleTimeslot\Models\ScheduleTimeslot::query()
            ->where('user_id', $data['id'])
            ->get()
            ->groupBy('day_of_week')
            ->mapWithKeys(fn ($slots, $day) => ["{$day}_timesheet" => $slots->map(fn ($slot) => [
                'start_time' => $slot->start_time->format('H:i'),
                'end_time'   => $slot->end_time->format('H:i'),
            ])->values()->all()]);

        return array_merge($data, $grouped->toArray());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->id]);
    }
}
