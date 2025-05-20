<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\ConfigProvider;

/**
 * Class BookingStatusTransitionService
 *
 * @package Modules\Booking\Services
 */
class BookingStatusTransitionService
{
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function handle(): void
    {
        $deadlineTime = $this->getCancellationDeadlineTime();

        Booking::where('status', BookingStatus::PENDING)
            ->whereRaw('TIMESTAMPDIFF(MINUTE, UTC_TIMESTAMP(), slot_start_at) BETWEEN 0 AND ?', [$deadlineTime])
            ->update(['status' => BookingStatus::CONFIRMED]);
    }

    private function getCancellationDeadlineTime()
    {
        return $this->configProvider->getBookingCancellationDeadline();
    }
}
