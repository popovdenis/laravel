<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingQuote;
use Modules\Payment\Contracts\RequestDataInterface;
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

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function create(RequestDataInterface $requestData): BookingQuoteInterface
    {
        $quote = new BookingQuote();
        $quote->setUser($requestData->student);
        $quote->setSlotId($requestData->slotId);
        $quote->setStreamId($requestData->streamId);
        $quote->setAmount($this->getBookingAmount($requestData));
        $quote->setModel(app(Booking::class));
        $quote->getPayment()->importData($requestData);

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
