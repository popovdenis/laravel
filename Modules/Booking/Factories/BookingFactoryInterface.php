<?php

namespace Modules\Booking\Factories;

use App\Models\Booking\BookingInterface;

/**
 * Interface BookingFactoryInterface
 *
 * @package App\Factories
 */
interface BookingFactoryInterface
{
    public function create(): BookingInterface;
}
