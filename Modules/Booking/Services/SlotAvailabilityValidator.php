<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\SlotAvailabilityValidatorInterface;
use Modules\Booking\Exceptions\SlotUnavailableException;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;

/**
 * Class SlotAvailabilityValidator
 *
 * @package App\Services\Booking
 */
class SlotAvailabilityValidator implements SlotAvailabilityValidatorInterface
{
    public function validate(BookingQuoteInterface $bookingQuote): void
    {
        $slot = ScheduleTimeslot::where('id', $bookingQuote->getSlotId())->exists();

        if (!$slot) {
            throw new SlotUnavailableException('Selected time slot is not available.');
        }

        // TODO: check on group limit, additional limits
//        $bookedCount = $slot->bookings()->where('status', 'confirmed')->count();
//        if ($slot->max_participants && $bookedCount >= $slot->max_participants) {
//            throw new SlotUnavailableException('The slot is fully booked.');
//        }
    }
}
