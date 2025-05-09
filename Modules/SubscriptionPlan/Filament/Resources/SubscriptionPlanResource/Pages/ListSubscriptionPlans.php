<?php

namespace Modules\SubscriptionPlan\Filament\Resources\SubscriptionPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\SubscriptionPlan\Filament\Resources\SubscriptionPlanResource;

class ListSubscriptionPlans extends ListRecords
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New Plan'),
        ];
    }
}
