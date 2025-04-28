<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Booking\Contracts\BookingInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Models\Enums\PaymentMethod;

/**
 * Class PaymentMethodResolver
 *
 * @package App\Services\Payment
 */
class PaymentMethodResolver
{
    public function resolve(PaymentMethod $method, BookingInterface $booking): PaymentMethodInterface
    {
        $payment = match ($method) {
            PaymentMethod::CREDITS => app(CreditsPaymentMethod::class),
            PaymentMethod::STRIPE  => app(StripePaymentMethod::class),
            default                => throw new \InvalidArgumentException('Unsupported payment method.'),
        };
        $payment->setBooking($booking);

        return $payment;
    }
}
