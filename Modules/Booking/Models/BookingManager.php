<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Enums\BookingStatus;
use Illuminate\Support\Collection;
use Modules\BookingGridFlat\Models\BookingGridFlat;

/**
 * Class BookingManager
 *
 * @package Modules\Booking\Models
 */
class BookingManager
{
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function getConfirmedBookings(): Collection
    {
        return Booking::leftJoin('booking_grid_flat', 'bookings.id', '=', 'booking_grid_flat.booking_id')
            ->whereNull('booking_grid_flat.booking_id')
            ->whereIn('bookings.status', [BookingStatus::PENDING, BookingStatus::CONFIRMED])
            ->select('bookings.*')
            ->get();
    }

    public function getUpcomingBookings()
    {
        $cancellationDeadlineTime = $this->getCancellationDeadlineTime();
        $minimalToStart = 5;
        $now = now();

        $query = BookingGridFlat::where('status', BookingStatus::PENDING)
            ->whereRaw("start_time >= DATE_ADD('{$now->toDateTimeString()}', INTERVAL {$minimalToStart} MINUTE)")
            ->whereRaw("start_time <= DATE_ADD('{$now->toDateTimeString()}', INTERVAL {$cancellationDeadlineTime} MINUTE)")
            ;

        dd($now->toDateTimeString(), $minimalToStart, $cancellationDeadlineTime, $query->toSql())

        return $query->get();
    }

    private function getCancellationDeadlineTime()
    {
        return $this->configProvider->getBookingCancellationDeadline() ?? config('booking.rules.cancellation_deadline');
    }
}
