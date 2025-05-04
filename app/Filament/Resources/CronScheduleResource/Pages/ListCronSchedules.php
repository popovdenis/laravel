<?php

namespace App\Filament\Resources\CronScheduleResource\Pages;

use App\Filament\Resources\CronScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
