<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Enums\BookingAction;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\PurchasableTransactionHandlerInterface;

/**
 * Class BookingTransactionHandler
 *
 * @package Modules\Booking\Models
 */
class BookingTransactionHandler implements PurchasableTransactionHandlerInterface
{
    public function handleOrderPlaced(OrderInterface $order): void
    {
        $booking = $order->purchasable;
        BookingCreditHistory::create([
            'user_id'        => $booking->student_id,
            'booking_id'     => $booking->id,
            'credits_amount' => -$order->getTotalAmount(),
            'action'         => BookingAction::SPEND->value,
            'comment'        => "Order #{$order->id} placed",
        ]);
    }

    public function handleOrderCancelled(OrderInterface $order): void
    {
        $booking = $order->purchasable;
        BookingCreditHistory::create([
            'user_id'        => $booking->student_id,
            'booking_id'     => $booking->id,
            'credits_amount' => $order->getTotalAmount(),
            'action'         => BookingAction::REFUND->value,
            'comment'        => "Order #{$order->id} cancelled",
        ]);
    }
}
