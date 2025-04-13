<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
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
        $teacher = \App\Models\User::find($data['teacher_id']);
        if ($teacher) {
            $meetingData = new \App\Data\MeetingData(
                teacherEmail: $teacher->email,
                startTime: \Illuminate\Support\Carbon::parse($data['start_time']),
                duration: $data['duration'] ?? 60,
                topic: 'Lesson with ' . $teacher->name,
            );

            $zoomDetails = app(ZoomService::class)->create($meetingData);

            if ($zoomDetails) {
                $data = array_merge($data, $zoomDetails);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return ScheduleResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
