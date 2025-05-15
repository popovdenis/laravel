<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Carbon\Carbon;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingQuote;
use Modules\Payment\Contracts\RequestDataInterface;
use Modules\ScheduleTimeslot\Contracts\ScheduleTimeslotRepositoryInterface;
use Modules\Subscription\Models\ConfigProvider;

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
    private ScheduleTimeslotRepositoryInterface $scheduleTimeslotRepository;

    public function __construct(
        ConfigProvider $configProvider,
        ScheduleTimeslotRepositoryInterface $scheduleTimeslotRepository,
    )
    {
        $this->configProvider = $configProvider;
        $this->scheduleTimeslotRepository = $scheduleTimeslotRepository;
    }

    public function create(RequestDataInterface $requestData): BookingQuoteInterface
    {
        /** @var BookingQuote $quote */
        $quote = app()->make(BookingQuote::class);
        $quote->setUser($requestData->student);
        $quote->setSlot($this->getSlotById($requestData->slotId));
        $quote->setLessonType(BookingTypeEnum::from($requestData->lessonType));
        $quote->setStreamId($requestData->streamId);
        $quote->setAmount($this->getBookingAmount($requestData));
        $quote->setModel(app(Booking::class));
        $quote->getPayment()->importData($requestData);

        $this->initSlotStartAt($quote, $requestData);

        return $quote;
    }

    private function getBookingAmount(\Modules\Booking\Data\BookingData $bookingData): int
    {
        if ($bookingData->bookingType === BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL) {
            return $this->configProvider->getIndividualLessonPrice();
        }

        return $this->configProvider->getGroupLessonPrice();
    }

    private function getSlotById(int $slotId)
    {
        return $this->scheduleTimeslotRepository->getById($slotId);
    }

    private function initSlotStartAt(BookingQuote $quote, RequestDataInterface $requestData)
    {
        $student = $quote->getUser();
        $studentTz = $student->timeZoneId;

        $bookingDateTime = \Carbon\Carbon::parse($requestData->slotStartAt, $studentTz);

        $bookingDateTimeUTC = $bookingDateTime->copy()->setTimezone('UTC');

        $quote->getSlot()->setAttribute('slot_start_at', $bookingDateTimeUTC);
    }
}
