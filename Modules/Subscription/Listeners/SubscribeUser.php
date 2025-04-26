<?php
declare(strict_types=1);

namespace Modules\Subscription\Listeners;

use Illuminate\Auth\Events\Registered;

/**
 * Class SubscribeUser
 *
 * @package App\Listeners
 */
class SubscribeUser
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->assignRole('Student');

        app(\App\Services\SubscriptionService::class)
            ->syncSubscriptionForUser($event->user, (int) $event->subscriptionPlanId);
    }
}
