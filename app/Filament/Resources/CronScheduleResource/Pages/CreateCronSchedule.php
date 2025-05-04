<?php

namespace App\Filament\Resources\CronScheduleResource\Pages;

use App\Filament\Resources\CronScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCronSchedule extends CreateRecord
{
    use CronScheduleTrait;

    protected static string $resource = CronScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->convertDataBeforeSave($data);
    }

    protected function afterCreate()
    {
        setting(['sync:schedule-monitor' => 1]);
    }
}
