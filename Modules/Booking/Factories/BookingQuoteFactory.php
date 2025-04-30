<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\BookingQuote;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;
use Modules\Stream\Models\Stream;
use Modules\Subscription\Models\ConfigProvider;
use Modules\User\Models\User;

/**
 * Class BookingQuoteFactory
 *
 * @package Modules\Booking\Factories
 */
class BookingQuoteFactory
{
    /**
     * @var \Modules\Subscription\Models\ConfigProvider
     */
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function create(\Modules\Booking\Data\BookingData $bookingData): BookingQuoteInterface
    {
        $quote = new BookingQuote();
        $quote->setUser($bookingData->student);
        $quote->setSlotId($bookingData->slotId);
        $quote->setStreamId($bookingData->streamId);
        $quote->setAmount($this->getBookingAmount($bookingData));

        return $quote;
    }

    private function getBookingAmount(\Modules\Booking\Data\BookingData $bookingData): int
    {
        if ($bookingData->bookingType === BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL) {
            return $this->configProvider->getIndividualLessonPrice();
        }

        return $this->configProvider->getGroupLessonPrice();
    }
}
