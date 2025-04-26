<?php

namespace App\Services\Payment;

use App\Models\Booking\BookingInterface;

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
