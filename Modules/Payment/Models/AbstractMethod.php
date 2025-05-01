<?php
declare(strict_types=1);

namespace Modules\Payment\Models;

use Illuminate\Validation\ValidationException;
use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\MethodInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;

/**
 * Class AbstractMethod
 *
 * @package Modules\Payment\Models
 */
abstract class AbstractMethod implements
    MethodInterface,
    PaymentMethodInterface
{
    /**
     * @var string
     */
    protected $_code;

    protected ?OrderInterface $order = null;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getCode()
    {
        if (empty($this->_code)) {
            throw ValidationException::withMessages([
                'payment' => [__('We cannot retrieve the payment method code.')],
            ]);
        }

        return $this->_code;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function validate(OrderInterface $order): void
    {
    }

    public function processAction()
    {
    }

    public function cancel()
    {
    }
}
