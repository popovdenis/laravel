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
        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = app(SubscriptionService::class);

        try {
            $subscriptionPlanId = $this->form->getRawState()['subscription_plan_id'] ?? null;
            $synced = $subscriptionService->syncSubscriptionForUser($this->record, $subscriptionPlanId);

            if (! $synced) {
                $newCreditBalance = (int) $this->form->getRawState()['credit_balance'] ?? 0;
                if ($this->record->credit_balance !== $newCreditBalance) {
                    $subscriptionService->replaceCreditBalance($this->record, $newCreditBalance);
                }
            }
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
