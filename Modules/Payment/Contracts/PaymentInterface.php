<?php

namespace Modules\Payment\Contracts;

/**
 * Interface PaymentInterface
 *
 * @package Modules\Payment\Contracts
 */
interface PaymentInterface
{
    /**
     * Get payment method code
     *
     * @return string
     */
    public function getMethod();

    /**
     * Set payment method code
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method);
}
