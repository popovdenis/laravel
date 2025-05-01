<?php

namespace Modules\Payment\Contracts;

/**
 * Interface InfoInterface
 *
 * @package Modules\Payment\Contracts
 */
interface InfoInterface
{
    /**
     * Retrieve payment method model object
     *
     * @return MethodInterface
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getMethodInstance();
}
