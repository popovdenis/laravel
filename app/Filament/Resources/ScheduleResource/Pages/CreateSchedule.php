<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ZoomService;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    public function create(bool $another = false): void
    {
        $teacherEmail = $this->data['teacher']['email'] ?? null;

        if ($teacherEmail && app(ZoomService::class)->isProAccount($teacherEmail)) {
            // call the method to create a meeting
        } else {
            // warn that the custom link is required
        }
    }
}
