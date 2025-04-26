<?php

namespace App\Factories;

use App\Models\Booking\BookingInterface;
use Illuminate\Http\Request;

/**
 * Interface BookingFactoryInterface
 *
 * @package App\Factories
 */
interface BookingFactoryInterface
{
    public function create(): BookingInterface;
}
