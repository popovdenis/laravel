<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Illuminate\Database\Eloquent\Collection;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Models\ConfigProvider;
use Modules\Booking\Models\SlotContext;

/**
 * Class BookingTypeConflictValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class BookingTypeValidator
{
    private CustomerTimezone $timezone;
    private ConfigProvider   $configProvider;

    public function __construct(CustomerTimezone $timezone, ConfigProvider $configProvider)
    {
        $this->timezone = $timezone;
        $this->configProvider = $configProvider;
    }

    /**
     * @throws BookingValidationException
     */
    public function validate(SlotContextInterface $slotContext): void
    {
        if ($slotContext->getLessonType() === BookingTypeEnum::BOOKING_TYPE_GROUP) {
            $this->validateGroupBooking($slotContext);
        } else if ($slotContext->getLessonType() === BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL) {
            $this->validateIndividualBooking($slotContext);
        }
    }

    private function validateGroupBooking(SlotContextInterface $slotContext): void
    {
        if ($this->hasIndividualBookings($slotContext)) {
            throw new BookingValidationException(
                'Group slots cannot be booked if individual bookings exist at the same time'
            );
        }

        $maxMembersAllowed = $this->configProvider->getMaximumGroupMembersCapacity();
        if ($this->isGroupLimitExceeded($slotContext, $maxMembersAllowed)) {
            throw new BookingValidationException(sprintf(
                'This group slot cannot accept more than %s participants.', $maxMembersAllowed
            ));
        }
    }

    private function validateIndividualBooking(SlotContextInterface $slotContext): void
    {
        // has Group bookings
        if ($this->getBookings($slotContext)->isNotEmpty()) {
            throw new BookingValidationException(
                'Individual slots cannot be booked when a group session is already scheduled.'
            );
        }
    }

    private function hasIndividualBookings(SlotContext $bookingSlot): bool
    {
        return $this->getBookings($bookingSlot)
                    ->where('lesson_type', BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL->value)
                    ->isNotEmpty();
    }

    private function isGroupLimitExceeded(SlotContext $bookingSlot, int $maxMembersAllowed): bool
    {
        return $this->getBookings($bookingSlot)
                    ->where('lesson_type', BookingTypeEnum::BOOKING_TYPE_GROUP->value)
                    ->count() >= $maxMembersAllowed;
    }

    private function getBookings(SlotContext $slotContext): Collection
    {
        $studentTz = $slotContext->getStudent()->timeZoneId;
        $start = $this->timezone->date($slotContext->getSlotStart(), $studentTz);
        $end = $start->copy()->addMinutes($slotContext->getSlotLength());

        $bookings = $slotContext->getDaySlot()->bookings->where('status', '!=', BookingStatus::CANCELLED);

        return $bookings->filter(function ($booking) use ($start, $end, $studentTz) {
            return $this->timezone->date($booking->slot_start_at, $studentTz)->between($start, $end) ||
                $this->timezone->date($booking->slot_end_at, $studentTz)->between($start, $end);
        });
    }
}