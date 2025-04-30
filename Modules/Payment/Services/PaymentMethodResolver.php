<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Enums\PaymentMethod;

/**
 * Class PaymentMethodResolver
 *
 * @package App\Services\Payment
 */
class PaymentMethodResolver
{
    public function resolve(string $method, OrderInterface $order): PaymentMethodInterface
    {
        $payment = match ($method) {
            PaymentMethod::CREDITS->value => app(CreditsPaymentMethod::class),
            PaymentMethod::STRIPE->value  => app(StripePaymentMethod::class),
            default                => throw new \InvalidArgumentException('Unsupported payment method.'),
        };
        $payment->setOrder($order);

        return $payment;
    }
}
