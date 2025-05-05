<?php

namespace Modules\Subject\Filament\Resources\SubjectResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Subject\Filament\Resources\SubjectResource;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
