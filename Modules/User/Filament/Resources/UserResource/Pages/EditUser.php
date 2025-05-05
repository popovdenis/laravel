<?php

namespace Modules\User\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Modules\User\Filament\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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

    protected function beforeSave(): void
    {
        $data = $this->form->getRawState();

        if (!empty($data['change_password'])) {
            if (!Hash::check($data['old_password'], $this->record->password)) {
                $this->addError('old_password', 'Current password is incorrect.');
                return;
            }

            if (!empty($data['new_password']) && $data['new_password'] === $data['new_password_confirmation']) {
                $this->record->password = Hash::make($data['new_password']);
            } else {
                $this->addError('new_password_confirmation', 'New passwords do not match.');
            }
        }
    }
}
