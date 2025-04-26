<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Data\MeetingData;
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
            $meeting = $this->prepareMeetingData($data);
            $data = array_merge(
                $data,
                empty($data['zoom_meeting_id']) && $meeting
                    ? $this->createMeeting($meeting)
                    : $this->updateMeeting($meeting)
            );
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
        app(ScheduleNotifier::class)->notifyParticipants($this->record);
    }

    protected function createMeeting(MeetingData $meetingData): array
    {
        return app(ZoomService::class)->create($meetingData);
    }

    protected function updateMeeting(MeetingData $meetingData): array
    {
        return app(ZoomService::class)->update($meetingData);
    }

    protected function prepareMeetingData(array $data): ?MeetingData
    {
        $teacher = \Modules\User\Models\User::find($data['teacher_id']);
        if ($teacher) {
            return new \App\Data\MeetingData(
                teacherEmail: $teacher->email,
                startTime: \Illuminate\Support\Carbon::parse($data['start_time']),
                duration: $data['duration'] ?? 60,
                topic: 'Lesson with ' . $teacher->name,
            );
        }

        return null;
    }

    protected function getRedirectUrl(): ?string
    {
        return ScheduleResource::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function afterActionCalled(): void
    {
        if ($this->record->zoom_meeting_id) {
            app(\App\Services\ZoomService::class)->delete($this->record->zoom_meeting_id);
        }
    }
}
