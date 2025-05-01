<?php

namespace Modules\Order\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Contracts\PaymentMethodInterface;

/**
 * Interface OrderManagerInterface
 *
 * @package Modules\Order\Contracts
 */
interface OrderManagerInterface
{
    public function place(QuoteInterface $quote): OrderInterface;

    /**
     * Cancels a specified order.
     *
     * @param OrderInterface $order;
     *
     * @return bool
     */
    public function cancel(OrderInterface $order): bool;

    public function findOrderByEntity(Model $model): OrderInterface;
}
