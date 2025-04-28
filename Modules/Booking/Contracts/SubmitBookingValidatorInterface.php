<?php

namespace Modules\Booking\Contracts;

/**
 * Interface SubmitBookingValidatorInterface
 *
 * @package App\Services\Booking
 */
interface SubmitBookingValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
