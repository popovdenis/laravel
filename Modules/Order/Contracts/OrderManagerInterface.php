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

    public function cancel(OrderInterface $order): bool;

    public function findOrderByEntity(Model $model): OrderInterface;
}
