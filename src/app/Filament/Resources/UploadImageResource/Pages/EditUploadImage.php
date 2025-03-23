<?php

namespace App\Filament\Resources\UploadImageResource\Pages;

use App\Filament\Resources\UploadImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUploadImage extends EditRecord
{
    protected static string $resource = UploadImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
