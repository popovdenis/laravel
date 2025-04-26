<?php

namespace Modules\Booking\Services;

/**
 * Interface SlotAvailabilityValidatorInterface
 *
 * @package App\Services\Booking
 */

use App\Models\Booking\BookingInterface;

interface SlotAvailabilityValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
