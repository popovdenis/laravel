<?php

namespace App\Services\Booking;

use App\Data\BookingData;

/**
 * Interface BookingManagementInterface
 *
 * @package App\Services\Booking
 */
interface BookingManagementInterface
{
    public function submit(BookingData $bookingData);
}
