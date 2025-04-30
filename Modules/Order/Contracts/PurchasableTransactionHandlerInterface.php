<?php

namespace Modules\Order\Contracts;

/**
 * Interface PurchasableTransactionHandlerInterface
 *
 * @package Modules\Order\Contracts
 */
interface PurchasableTransactionHandlerInterface
{
    public function handleOrderPlaced(OrderInterface $order): void;

    public function handleOrderCancelled(OrderInterface $order): void;
}
