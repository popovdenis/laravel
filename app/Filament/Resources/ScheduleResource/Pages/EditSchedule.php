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

    protected function afterSave()
    {
        $this->createZoomMeeting($this->record);
    }

    protected function createZoomMeeting(Schedule $schedule): void
    {
        $zoom = app(ZoomService::class);
        $topic = 'Lesson with ' . ($schedule->teacher->name ?? 'Teacher');

        $meeting = $zoom->create($schedule, $topic, 60);

        $schedule->update($meeting);
    }
}
