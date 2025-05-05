<?php

namespace Modules\CronSchedule\Filament\Resources\CronScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CronSchedule\Filament\Resources\CronScheduleResource;

class ListCronSchedules extends ListRecords
{
    protected static string $resource = CronScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
