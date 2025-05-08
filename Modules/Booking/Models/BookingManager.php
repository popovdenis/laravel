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
    public function getConfirmedBookings(): Collection
    {
        return Booking::leftJoin('booking_grid_flat', 'bookings.id', '=', 'booking_grid_flat.booking_id')
            ->whereNull('booking_grid_flat.booking_id')
            ->whereIn('bookings.status', [BookingStatus::PENDING, BookingStatus::CONFIRMED])
            ->select('bookings.*')
            ->get();
    }
}
