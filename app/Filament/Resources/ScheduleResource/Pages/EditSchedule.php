<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Services\ScheduleNotifier;
use App\Services\ZoomService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['zoom_meeting_id']) || $data['reschedule']) {
            $data = array_merge($data, $this->createMeeting($data));
            $data['notify_user'] = 1;
            $data['user_notified'] = 0;

            foreach ($this->record->students as $student) {
                $this->record->students()->updateExistingPivot($student->id, [
                    'notify_user' => true,
                    'user_notified' => false,
                ]);
            }
        }

        return $data;
    }

    protected function afterSave()
    {
        ScheduleNotifier::notifyParticipants($this->record);
    }

    protected function createMeeting(array $data): array
    {
        $teacher = \App\Models\User::find($data['teacher_id']);
        if ($teacher) {
            $meetingData = new \App\Data\MeetingData(
                teacherEmail: $teacher->email,
                startTime: \Illuminate\Support\Carbon::parse($data['start_time']),
                duration: $data['duration'] ?? 60,
                topic: 'Lesson with ' . $teacher->name,
            );

            return app(ZoomService::class)->create($meetingData);
        }

        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return ScheduleResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
