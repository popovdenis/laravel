<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Enums\BookingStatus;
use Illuminate\Support\Collection;

/**
 * Class BookingManager
 *
 * @package Modules\Booking\Models
 */
class BookingManager
{
    public function getConfirmedBookings(array $excludeBookingIds = []): Collection
    {
        $bookings = Booking::whereIn('status', [BookingStatus::PENDING, BookingStatus::CONFIRMED]);// TODO: remove PENDING!

        if ($excludeBookingIds) {
            $bookings->whereNotIn('id', $excludeBookingIds);
        }

        return $bookings->get();
    }
}
