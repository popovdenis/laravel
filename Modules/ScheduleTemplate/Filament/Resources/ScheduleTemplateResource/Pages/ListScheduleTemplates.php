<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;

class ListScheduleTemplates extends ListRecords
{
    protected static string $resource = ScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
