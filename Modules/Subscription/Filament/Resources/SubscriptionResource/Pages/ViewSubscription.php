<?php
declare(strict_types=1);

namespace Modules\Subscription\Filament\Resources\SubscriptionResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Subscription\Filament\Resources\SubscriptionResource;

/**
 * Class ViewSubscription
 *
 * @package App\Filament\Resources\SubscriptionResource\Pages
 */
class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;
}
