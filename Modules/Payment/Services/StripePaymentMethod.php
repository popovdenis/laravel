<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Booking\Contracts\BookingInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;

/**
 * Class StripePaymentMethod
 *
 * @package App\Services\Payment
 */
class StripePaymentMethod implements PaymentMethodInterface
{
    protected BookingInterface $booking;

    public function validate(BookingInterface $booking): void
    {
        // TODO: Implement validate() method.
    }

    public function authorize(BookingInterface $booking): void
    {
        // TODO: Implement authorize() method.
    }

    public function setBooking(BookingInterface $booking): void
    {
        $this->booking = $booking;
    }

    public function getBooking(): BookingInterface
    {
        return $this->booking;
    }

    public function place()
    {
        // TODO: Implement place() method.
    }

    public function cancel()
    {

    }
}
