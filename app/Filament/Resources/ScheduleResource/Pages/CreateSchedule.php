<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ZoomService;
use Filament\Notifications\Notification;
use App\Factories\MeetingFactory;
use App\Data\MeetingData;
use App\Models\User;
use Illuminate\Support\Carbon;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $teacherId = $data['teacher_id'] ?? null;
        $teacher = \App\Models\User::find($teacherId);

        if (! $teacher) {
            Notification::make()
                ->title('Teacher not found.')
                ->danger()
                ->send();

            $this->halt();
        }

        $teacher = User::find($this->data['teacher_id']);
        if ($teacher) {
            $meetingData = new MeetingData(
                teacherEmail: $teacher->email,
                startTime: Carbon::parse($this->data['start_time']),
                duration: $data['duration'] ?? 60,
                topic: 'Lesson with ' . $teacher->name,
            );

            $zoom = app(ZoomService::class)->create($meetingData);
            if ($zoom) {
                $data = array_merge($data, $zoom);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return ScheduleResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
