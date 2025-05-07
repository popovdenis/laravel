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
    public function handle(array $data): void
    {
        $customer = $data['customer'];
        $customerData = $data['customer_data'];

        $customer->assignRole('Student');
        $plan = $this->subscriptionService->getSubscriptionPlan((int) $customerData->subscriptionPlanId);
        $this->subscriptionService->updateCreditBalance($customer, $plan);
        //TODO: subscribe customer to the selected subscription
    }
}
