<?php

namespace Modules\Booking\Services;

use Modules\Booking\Models\BookingInterface;

/**
 * Interface SubmitBookingValidatorInterface
 *
 * @package App\Services\Booking
 */
interface SubmitBookingValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
