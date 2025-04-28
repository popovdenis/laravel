<?php

namespace Modules\Booking\Contracts;

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
    public function cancel(\Modules\Booking\Contracts\BookingInterface $booking): bool;

    /**
     * Gets the status for a specified booking.
     *
     * @param int $id The booking ID.
     *
     * @return string Booking status.
     */
    public function getStatus($id);

    /**
     * @param \Modules\Booking\Contracts\BookingInterface $booking
     *
     * @return \Modules\Booking\Contracts\BookingInterface
     */
    public function place(\Modules\Booking\Contracts\BookingInterface $booking): BookingInterface;
}
