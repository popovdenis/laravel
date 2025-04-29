<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Data\MeetingData;
use App\Filament\Resources\ScheduleResource;
use App\Services\ZoomService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $teacherId = $data['teacher_id'] ?? null;
        $teacher = \Modules\User\Models\User::find($teacherId);

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
                topic: 'Lesson with ' . $teacher->getFullNameAttribute(),
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
