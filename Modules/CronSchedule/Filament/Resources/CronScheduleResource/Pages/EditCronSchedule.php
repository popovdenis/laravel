<?php

namespace Modules\CronSchedule\Filament\Resources\CronScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CronSchedule\Filament\Resources\CronScheduleResource;

class EditCronSchedule extends EditRecord
{
    use CronScheduleTrait;

    protected static string $resource = CronScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->convertDataBeforeSave($data);
    }

    protected function afterSave()
    {
        setting(['sync:schedule-monitor' => 1]);
    }
}
