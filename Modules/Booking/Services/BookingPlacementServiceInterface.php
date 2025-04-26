<?php

namespace Modules\Booking\Services;

/**
 * Interface BookingPlacementServiceInterface
 *
 * @package App\Services\Booking
 */
interface BookingPlacementServiceInterface
{
    /**
     * Cancels a specified booking.
     *
     * @param int $id The booking ID.
     *
     * @return bool
     */
    public function cancel($id);

    /**
     * Gets the status for a specified booking.
     *
     * @param int $id The booking ID.
     *
     * @return string Booking status.
     */
    public function getStatus($id);

    /**
     * @param \App\Models\Booking\BookingInterface $booking
     *
     * @return \App\Models\Booking\BookingInterface
     */
    public function place(\App\Models\Booking\BookingInterface $booking);
}
