<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState()['currentTranslation'];

        $data['lang_id'] = 1;
        $data['post_id'] = $record->id;

        \App\Blog\Models\PostTranslation::updateOrCreate(
            ['post_id' => $record->id, 'lang_id' => 1],
            $data
        );
    }
}
