<?php

namespace Modules\Booking\Services;

use Modules\Booking\Data\BookingData;

/**
 * Interface BookingManagementInterface
 *
 * @package App\Services\Booking
 */
interface BookingManagementInterface
{
    public function submit(BookingData $bookingData);
}
