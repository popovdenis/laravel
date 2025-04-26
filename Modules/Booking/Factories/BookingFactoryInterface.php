<?php

namespace Modules\Booking\Factories;

use Modules\Booking\Models\BookingInterface;

/**
 * Interface BookingFactoryInterface
 *
 * @package App\Factories
 */
interface BookingFactoryInterface
{
    public function create(): BookingInterface;
}
