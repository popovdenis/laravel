<?php
namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        $translation = $record->currentTranslation;

        $data['currentTranslation'] = $translation ? $translation->toArray() : [];
        $data['is_published'] = $record->is_published;
        $data['posted_at'] = $record->posted_at;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState()['currentTranslation'];

        $record->currentTranslation()->updateOrCreate(
            ['lang_id' => 1],
            $data
        );
    }
}
