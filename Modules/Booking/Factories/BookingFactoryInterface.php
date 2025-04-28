<?php

namespace Modules\Booking\Factories;

use Modules\Booking\Contracts\BookingInterface;

/**
 * Interface BookingFactoryInterface
 *
 * @package App\Factories
 */
interface BookingFactoryInterface
{
    public function create(): BookingInterface;
}
