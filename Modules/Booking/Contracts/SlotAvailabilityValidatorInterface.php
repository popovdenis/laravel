<?php

namespace Modules\Booking\Contracts;

/**
 * Interface SlotAvailabilityValidatorInterface
 *
 * @package App\Services\Booking
 */
interface SlotAvailabilityValidatorInterface
{
    public function validate(BookingInterface $booking): void;
}
