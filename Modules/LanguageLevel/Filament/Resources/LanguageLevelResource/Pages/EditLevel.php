<?php

namespace Modules\LanguageLevel\Filament\Resources\LanguageLevelResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\LanguageLevel\Filament\Resources\LanguageLevelResource;

class EditLevel extends EditRecord
{
    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
