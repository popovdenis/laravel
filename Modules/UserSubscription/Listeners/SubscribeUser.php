<?php
declare(strict_types=1);

namespace Modules\UserSubscription\Listeners;

use Illuminate\Auth\Events\Registered;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Subscription\Data\SubscriptionData;
use Modules\Subscription\Factories\SubscriptionQuoteFactory;
use Modules\Subscription\Services\SubscriptionService;

/**
 * Class SubscribeUser
 *
 * @package App\Listeners
 */
class SubscribeUser
{
    private SubscriptionService $subscriptionService;
    private OrderManagerInterface $orderManager;
    private SubscriptionQuoteFactory $quoteFactory;

    public function __construct(
        SubscriptionService $subscriptionService,
        OrderManagerInterface $orderManager,
        SubscriptionQuoteFactory $quoteFactory
    )
    {
        $this->subscriptionService = $subscriptionService;
        $this->orderManager = $orderManager;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * Handle the event.
     */
    public function handle(array $data): void
    {
        $customer = $data['customer'];
        $customerData = $data['customer_data'];

        $customer->assignRole('Student');

        $subscriptionData = SubscriptionData::fromModel($customer, (int) $customerData->subscriptionPlanId);

        $quote = $this->quoteFactory->create($subscriptionData);
        $this->orderManager->place($quote);
    }
}
