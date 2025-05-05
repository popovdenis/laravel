<?php

namespace Modules\Stream\Filament\Resources\StreamResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Stream\Filament\Resources\StreamResource;

class ListStreams extends ListRecords
{
    protected static string $resource = StreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
