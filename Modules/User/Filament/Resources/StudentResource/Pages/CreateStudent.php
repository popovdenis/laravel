<?php

namespace Modules\User\Filament\Resources\StudentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\User\Filament\Resources\StudentResource;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function afterCreate(): void
    {
        $subscriptionPlanId = $this->form->getRawState()['subscription_plan_id'] ?? null;
        if ($subscriptionPlanId) {
            $this->record->userSubscription()->updateOrCreate([], ['plan_id' => $subscriptionPlanId]);
        } else {
            $this->record->userSubscription()?->delete();
        }
    }
}
