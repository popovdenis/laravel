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
    public function getTitle();

    public function validate(OrderInterface $order): void;

    public function authorize(OrderInterface $order): void;

    public function setOrder(OrderInterface $order): void;

    public function getOrder(): OrderInterface;

    public function place();

    public function cancel();
}
