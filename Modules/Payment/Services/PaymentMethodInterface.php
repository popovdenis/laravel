<?php

namespace Modules\Payment\Services;

use Modules\Booking\Models\BookingInterface;

/**
 * Interface PaymentMethodInterface
 *
 * @package App\Services\Payment
 */
interface PaymentMethodInterface
{
    public function validate(BookingInterface $booking): void;

    public function authorize(BookingInterface $booking): void;

    public function setBooking(BookingInterface $booking): void;

    public function getBooking(): BookingInterface;

    public function place();
}
