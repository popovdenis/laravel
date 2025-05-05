<?php

namespace Modules\Stream\Filament\Resources\StreamResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Stream\Filament\Resources\StreamResource;

class EditStream extends EditRecord
{
    protected static string $resource = StreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
