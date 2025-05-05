<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Enums\PaymentMethod;
use Modules\Payment\Models\Payment;
use Modules\User\Models\User;

/**
 * Class StripePaymentMethod
 *
 * @package App\Services\Payment
 */
class StripePaymentMethod implements PaymentMethodInterface
{
    protected OrderInterface $order;

    public function validate(OrderInterface $order): void
    {
        // TODO: Implement validate() method.
    }

    public function processAction()
    {
    }

    public function cancel()
    {
    }

    public function getTitle()
    {
        return setting('payment.stripe.title');
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getCode()
    {
        // TODO: Implement getCode() method.
    }
}
