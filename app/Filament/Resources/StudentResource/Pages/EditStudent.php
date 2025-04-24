<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\SubscriptionService;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $subscriptionPlanId = $this->form->getRawState()['subscription_plan_id'] ?? null;

        app(SubscriptionService::class)->syncForUser($this->record, $subscriptionPlanId);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['subscription_plan_id'] = $this->record->subscription?->first()->plan_id;

        return $data;
    }
}
