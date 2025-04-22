<?php

namespace App\Filament\Resources\LanguageLevelResource\Pages;

use App\Filament\Resources\LanguageLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevel extends ListRecords
{
    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
