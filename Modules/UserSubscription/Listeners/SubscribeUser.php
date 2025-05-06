<?php
declare(strict_types=1);

namespace Modules\UserSubscription\Listeners;

use Illuminate\Auth\Events\Registered;
use Modules\Subscription\Services\SubscriptionService;

/**
 * Class SubscribeUser
 *
 * @package App\Listeners
 */
class SubscribeUser
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->assignRole('Student');
        $this->subscriptionService->syncSubscriptionForUser($event->user, (int) $event->subscriptionPlanId);
    }
}
