<?php

namespace App\Filament\Resources\CronScheduleResource\Pages;

use App\Filament\Resources\CronScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCronSchedule extends EditRecord
{
    protected static string $resource = CronScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
