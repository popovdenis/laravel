<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\ConfigProvider;

/**
 * Class BookingSlotService
 *
 * @package Modules\Booking\Services
 */
class BookingSlotService
{
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function getDefaultLessonType()
    {
        return $this->configProvider->getDefaultLessonType();
    }

    public function getInitialDaysToShow()
    {
        return $this->configProvider->getInitialDaysToShow();
    }

    public function getLessonEnumType($lessonType)
    {
        return BookingTypeEnum::from($lessonType);
    }

    public function getChunkLength(BookingTypeEnum $lessonType): int
    {
        return match ($lessonType) {
            BookingTypeEnum::BOOKING_TYPE_GROUP => $this->configProvider->getBookingGroupLessonDuration(),
            BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL => $this->configProvider->getBookingIndividualLessonDuration(),
        };
    }

}