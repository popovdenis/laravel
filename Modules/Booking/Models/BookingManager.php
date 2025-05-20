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

    public function getUpcomingBookings()
    {
        $cancellationDeadlineTime = $this->getCancellationDeadlineTime();

        $bookings = Booking::where('status', BookingStatus::PENDING)->get();

        $minimalToStart = 5;
        $timezone = new \DateTimeZone('Europe/Warsaw');
        $now = now($timezone);

        return BookingGridFlat::where('status', BookingStatus::PENDING)
            ->whereRaw("start_time >= DATE_ADD('{$now->toDateTimeString()}', INTERVAL {$minimalToStart} MINUTE)")
            ->whereRaw("start_time <= DATE_ADD('{$now->toDateTimeString()}', INTERVAL {$cancellationDeadlineTime} MINUTE)")
            ->get();
    }

    private function getCancellationDeadlineTime()
    {
        return $this->configProvider->getBookingCancellationDeadline();
    }
}
