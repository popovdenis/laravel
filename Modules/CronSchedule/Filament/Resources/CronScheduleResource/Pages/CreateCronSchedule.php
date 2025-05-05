<?php

namespace Modules\CronSchedule\Filament\Resources\CronScheduleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CronSchedule\Filament\Resources\CronScheduleResource;

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
