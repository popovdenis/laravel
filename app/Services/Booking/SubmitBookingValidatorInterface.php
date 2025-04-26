<?php

namespace App\Services\Booking;

use App\Models\Booking\BookingInterface;

/**
 * Interface SubmitBookingValidatorInterface
 *
 * @package App\Services\Booking
 */
interface SubmitBookingValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
