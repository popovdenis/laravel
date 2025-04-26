<?php

namespace Modules\Booking\Services;

/**
 * Interface SlotAvailabilityValidatorInterface
 *
 * @package App\Services\Booking
 */

use Modules\Booking\Models\BookingInterface;

interface SlotAvailabilityValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
