<?php

namespace Modules\Order\Contracts;

/**
 * Interface OrderPlacementServiceInterface
 *
 * @package Modules\Order\Contracts
 */
interface OrderPlacementServiceInterface
{
    /**
     * Cancels a specified order.
     *
     * @param OrderInterface $order
     *
     * @return bool
     */
    public function cancel(OrderInterface $order): bool;

    /**
     * Gets the status for a specified order.
     *
     * @param int $id The order ID.
     *
     * @return string Booking status.
     */
    public function getStatus($id);

    /**
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function place(OrderInterface $order): OrderInterface;
}
