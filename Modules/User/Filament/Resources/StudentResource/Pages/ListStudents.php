<?php

namespace Modules\User\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\User\Filament\Resources\StudentResource;

class ListStudents extends ListRecords
{
    protected static ?string $title = 'Students';
    protected static ?string $breadcrumb = 'Students';
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
