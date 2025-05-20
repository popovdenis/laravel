<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Data\BookingData;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\SlotContext;
use Modules\Booking\Models\BookingQuote;
use Modules\Booking\Services\BookingSlotService;
use Modules\Payment\Contracts\RequestDataInterface;
use Modules\ScheduleTimeslot\Contracts\ScheduleTimeslotRepositoryInterface;
use Modules\Stream\Contracts\StreamRepositoryInterface;
use Modules\Stream\Models\Stream;
use Modules\Subscription\Models\ConfigProvider;

/**
 * Class BookingQuoteFactory
 *
 * @package Modules\Booking\Factories
 */
class BookingQuoteFactory
{
    private ConfigProvider                      $configProvider;
    private SlotContext                         $slotContext;
    private ScheduleTimeslotRepositoryInterface $scheduleTimeslotRepository;
    private BookingSlotService                  $bookingSlotService;
    private StreamRepositoryInterface           $streamRepository;
    private CustomerTimezone                    $timezone;

    public function __construct(
        ConfigProvider                      $configProvider,
        SlotContext                         $slotContext,
        BookingSlotService                  $bookingSlotService,
        StreamRepositoryInterface           $streamRepository,
        ScheduleTimeslotRepositoryInterface $scheduleTimeslotRepository,
        CustomerTimezone                    $timezone
    )
    {
        $this->configProvider             = $configProvider;
        $this->slotContext                = $slotContext;
        $this->bookingSlotService         = $bookingSlotService;
        $this->streamRepository           = $streamRepository;
        $this->scheduleTimeslotRepository = $scheduleTimeslotRepository;
        $this->timezone                   = $timezone;
    }

    public function create(RequestDataInterface $requestData): BookingQuoteInterface
    {
        $slot = $this->getSlotById($requestData->slotId);

        /** @var BookingQuote $quote */
        $quote = app()->make(BookingQuote::class);
        $quote->setStudent($requestData->student);
        $quote->setTeacher($slot->teacher);
        $quote->setSlot($slot);
        $quote->setLessonType($requestData->lessonType);
        $quote->setStreamId($requestData->streamId);
        $quote->setAmount($this->getBookingAmount($requestData));
        $quote->setModel(app(Booking::class));
        $quote->getPayment()->importData($requestData);

        $this->buildBookingSlot($quote, $requestData);

        return $quote;
    }

    private function buildBookingSlot(BookingQuote $quote, RequestDataInterface $requestData): void
    {
        $slotContext = $this->slotContext->create();
        $slotContext->setData([
            'teacher'       => $quote->getTeacher(),
            'student'       => $quote->getStudent(),
            'lesson_type'   => $quote->getLessonType(),
            'lesson_length' => $this->bookingSlotService->getChunkLength($quote->getLessonType()),
            'stream'        => $this->getStreamById($quote->getStreamId()),
            'slot_start'    => $this->timezone->date($requestData->slotStartAt, $quote->getStudent()->timeZoneId),
            'slot_end'      => $this->timezone->date($requestData->slotEndAt, $quote->getStudent()->timeZoneId),
            'day_slot'      => $quote->getSlot()
        ]);
        $slotContext->setData('current_subject', $this->getCurrentSubject($slotContext->getStream()));

        $quote->setSlotContext($slotContext);
    }

    private function getBookingAmount(RequestDataInterface $bookingData): int
    {
        if ($bookingData->lessonType === BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL) {
            return $this->configProvider->getIndividualLessonPrice();
        }

        return $this->configProvider->getGroupLessonPrice();
    }

    private function getSlotById(int $slotId)
    {
        return $this->scheduleTimeslotRepository->getById($slotId);
    }

    private function getStreamById(int $streamId)
    {
        return $this->streamRepository->getById($streamId);
    }

    private function getCurrentSubject(Stream $stream): \Modules\Subject\Models\Subject
    {
        return $stream->languageLevel->subjects->firstWhere('id', $stream->current_subject_id);
    }
}
