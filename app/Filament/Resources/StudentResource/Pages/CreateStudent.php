<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function afterCreate(): void
    {
        $subscriptionPlanId = $this->form->getRawState()['subscription_plan_id'] ?? null;
        if ($subscriptionPlanId) {
            $this->record->subscription()->updateOrCreate([], ['plan_id' => $subscriptionPlanId]);
        } else {
            $this->record->subscription()?->delete();
        }
    }
}
