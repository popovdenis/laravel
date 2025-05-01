<?php

namespace Modules\Payment\Contracts;

use Modules\Order\Contracts\OrderInterface;

/**
 * Interface MethodInterface
 *
 * @package Modules\Payment\Contracts
 */
interface MethodInterface
{
    public function getTitle();
    public function setOrder(OrderInterface $order);
    public function getOrder(): OrderInterface;
}
