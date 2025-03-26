<?php

namespace App\Filament\Resources\UploadImageResource\Pages;

use App\Filament\Resources\UploadImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUploadImages extends ListRecords
{
    protected static string $resource = UploadImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Upload Image'),
        ];
    }
}
