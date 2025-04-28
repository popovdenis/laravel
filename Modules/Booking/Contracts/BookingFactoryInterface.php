<?php

namespace Modules\Booking\Contracts;

/**
 * Interface BookingFactoryInterface
 *
 * @package App\Factories
 */
interface BookingFactoryInterface
{
    public function create(): BookingInterface;
}
