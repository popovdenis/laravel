<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Illuminate\Database\Eloquent\Collection;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Contracts\SlotValidatorInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Models\SlotContext;

/**
 * Class IndividualLessonValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class IndividualLessonValidator implements SlotValidatorInterface
{
    private CustomerTimezone $timezone;

    public function __construct(CustomerTimezone $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @throws BookingValidationException
     */
    public function validate(SlotContextInterface $slotContext): void
    {
        if ($slotContext->getLessonType() === BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL) {
            $this->validateIndividualBooking($slotContext);
        }
    }

    private function validateIndividualBooking(SlotContextInterface $slotContext): void
    {
        // has Group bookings
        if ($this->getBookings($slotContext)->isNotEmpty()) {
//            throw new BookingValidationException(
//                'Individual slots cannot be booked when a group session is already scheduled.'
//            );
        }
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