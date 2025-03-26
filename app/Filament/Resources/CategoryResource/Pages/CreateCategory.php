<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState()['categoryTranslation'];

        $data['lang_id'] = 1;
        $data['category_id'] = $record->id;

        \App\Blog\Models\CategoryTranslation::updateOrCreate(
            ['category_id' => $record->id, 'lang_id' => 1],
            $data
        );
    }
}
