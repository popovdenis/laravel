<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingTransactionHandler;
use Modules\Order\Enums\OrderActionEnum;
use Modules\Subscription\Models\Subscription;
use Laravel\Cashier\Subscription as CashierSubscription;
use Modules\Subscription\Models\SubscriptionTransactionHandler;

/**
 * Class OrderTransactionManager
 *
 * @package Modules\Order\Models
 */
class OrderTransactionManager
{
    public function handle(Order $order, OrderActionEnum $action): void
    {
        $purchasable = $order->purchasable;

        $handler = match (get_class($purchasable)) {
            Booking::class              => app(BookingTransactionHandler::class),
            Subscription::class         => app(SubscriptionTransactionHandler::class),
            CashierSubscription::class  => app(SubscriptionTransactionHandler::class),
            default => throw new \RuntimeException('No handler for this type')
        };

        match ($action) {
            OrderActionEnum::ORDER_ACTION_PLACED => $handler->handleOrderPlaced($order),
            OrderActionEnum::ORDER_ACTION_CANCELLED => $handler->handleOrderCancelled($order),
            default => throw new \InvalidArgumentException("Unknown action $action->value")
        };
    }
}
