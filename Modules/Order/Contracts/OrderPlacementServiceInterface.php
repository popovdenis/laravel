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
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function place(OrderInterface $order): OrderInterface;
}
