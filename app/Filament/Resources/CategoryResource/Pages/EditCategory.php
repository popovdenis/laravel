<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $translation = $data['categoryTranslation'];
        unset($data['categoryTranslation']);

        $this->record->categoryTranslations()->updateOrCreate(
            ['lang_id' => 1],
            $translation
        );

        return $data;
    }

    protected function fillForm(): void
    {
        $translation = $this->record->categoryTranslations()->where('lang_id', 1)->first();

        $this->form->fill(array_merge(
            $this->record->attributesToArray(),
            ['categoryTranslation' => $translation?->attributesToArray() ?? []]
        ));
    }
}
