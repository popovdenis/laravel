<?php

namespace Modules\Booking\Services;

use Modules\Booking\Models\BookingInterface;

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
    public function cancel(\Modules\Booking\Models\BookingInterface $booking): bool;

    /**
     * Gets the status for a specified booking.
     *
     * @param int $id The booking ID.
     *
     * @return string Booking status.
     */
    public function getStatus($id);

    /**
     * @param \Modules\Booking\Models\BookingInterface $booking
     *
     * @return \Modules\Booking\Models\BookingInterface
     */
    public function place(\Modules\Booking\Models\BookingInterface $booking): BookingInterface;
}
