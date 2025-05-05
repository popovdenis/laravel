<?php

namespace Modules\LanguageLevel\Filament\Resources\LanguageLevelResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\LanguageLevel\Filament\Resources\LanguageLevelResource;

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
