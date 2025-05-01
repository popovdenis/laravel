<?php

namespace Modules\Payment\Contracts;

use Modules\Order\Contracts\OrderInterface;

/**
 * Interface PaymentMethodInterface
 *
 * @package App\Services\Payment
 */
interface PaymentMethodInterface
{
    /**
     * Get payment method code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get payment method title
     *
     * @return string
     */
    public function getTitle();

    public function validate(OrderInterface $order): void;

    public function setOrder(OrderInterface $order);

    public function getOrder(): OrderInterface;

    public function processAction();

    public function cancel();
}
