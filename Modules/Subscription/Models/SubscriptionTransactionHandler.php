<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\PurchasableTransactionHandlerInterface;
use Modules\Subscription\Enums\TransactionStatus;

/**
 * Class SubscriptionTransactionHandler
 *
 * @package Modules\Subscription\Models
 */
class SubscriptionTransactionHandler implements PurchasableTransactionHandlerInterface
{
    public function handleOrderPlaced(OrderInterface $order): void
    {
        //TODO: do nothing for now
//        $subscription = $order->purchasable;
//        SubscriptionTransaction::create([
//            'user_id'        => $subscription->user_id,
//            'subscription_id'=> $order->getQuote()->subscription->id,//TODO: refactor
//            'credits_amount' => $subscription->plan->credits,
//            'action'         => TransactionStatus::PURCHASE,
//            'comment'        => "Order #{$order->id} placed",
//        ]);

        //insert into `subscription_transactions` (`user_id`, `subscription_id`, `credits_amount`, `comment`, `updated_at`, `created_at`) values (2, 15, 100, Order #19 placed, 2025-04-30 12:41:30, 2025-04-30 12:41:30))
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
