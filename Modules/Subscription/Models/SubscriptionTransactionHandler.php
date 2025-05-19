<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\PurchasableTransactionHandlerInterface;
use Modules\Subscription\Enums\TransactionStatus;
use Modules\Subscription\Services\SubscriptionService;

/**
 * Class SubscriptionTransactionHandler
 *
 * @package Modules\Subscription\Models
 */
class SubscriptionTransactionHandler implements PurchasableTransactionHandlerInterface
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function handleOrderPlaced(OrderInterface $order): void
    {
        $quote = $order->getQuote();
        $subscription = $order->getQuote()->getModel();

        $subscription->update($this->subscriptionService->getUpdateUserSubscriptionOptions($quote->getPlan()));
        $this->subscriptionService->updateCreditBalance($quote->getStudent(), $quote->getPlan());
    }

    public function handleOrderCancelled(OrderInterface $order): void
    {
        //TODO: do nothing for now
//        $subscription = $order->purchasable;
//        SubscriptionTransaction::create([
//            'user_id'        => $subscription->user_id,
//            'subscription_id'=> $subscription->id,
//            'credits_amount' => -$subscription->plan->credits,
//            'action'         => TransactionStatus::REFUND,
//            'comment'        => "Order #{$order->id} placed",
//        ]);
    }
}
