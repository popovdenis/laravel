<?php

namespace Modules\User\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Subscription\Services\SubscriptionService;
use Modules\User\Filament\Resources\StudentResource;

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

        try {
            app(SubscriptionService::class)->syncSubscriptionForUser($this->record, $subscriptionPlanId);
        } catch (\Exception $e) {
            report($e);
        }

        $this->fillForm();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['subscription_plan_id'] = $this->record->userSubscription?->first()->plan_id;

        return $data;
    }
}
