<?php

namespace Modules\Subscription\Filament\Resources\SubscriptionResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Subscription\Filament\Resources\SubscriptionResource;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Example: additional logic if needed
        return $data;
    }
}
