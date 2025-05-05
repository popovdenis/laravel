<?php

namespace Modules\User\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\User\Filament\Resources\TeacherResource;

class ListTeachers extends ListRecords
{
    protected static ?string $title = 'Teachers';
    protected static ?string $breadcrumb = 'Teachers';
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
