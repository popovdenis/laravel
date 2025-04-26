<?php
declare(strict_types=1);

namespace App\Services\Payment;

use Modules\Booking\Models\BookingInterface;

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
}
