<?php

namespace Modules\Booking\Services;

use Modules\Booking\Data\BookingData;
use Modules\Booking\Models\BookingInterface;

/**
 * Interface BookingManagementInterface
 *
 * @package App\Services\Booking
 */
interface BookingManagementInterface
{
    /**
     * @param BookingData $bookingData
     *
     * @return BookingInterface
     */
    public function place(BookingData $bookingData): BookingInterface;

    /**
     * Cancels a specified booking.
     *
     * @param BookingInterface $booking.
     *
     * @return bool
     */
    public function cancel(BookingInterface $booking): bool;
}
