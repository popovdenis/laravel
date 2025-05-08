<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Models\BookingManager;

/**
 * Class BookingStatusTransitionService
 *
 * @package Modules\Booking\Services
 */
class BookingStatusTransitionService
{
    private BookingManager $bookingManager;

    public function __construct(BookingManager $bookingManager)
    {
        $this->bookingManager = $bookingManager;
    }

    public function handle(): void
    {
        $bookings = $this->bookingManager->getUpcomingBookings();
        if ($bookings->isEmpty()) {
            return;
        }

        foreach ($bookings as $booking) {
            $booking->markAsConfirmed();
        }
    }
}
