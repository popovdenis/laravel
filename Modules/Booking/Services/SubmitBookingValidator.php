<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use App\Exceptions\AlreadyExistsException;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingInterface;

/**
 * Class SubmitBookingValidator
 *
 * @package App\Services\Booking
 */
class SubmitBookingValidator implements SubmitBookingValidatorInterface
{
    public function validate(BookingInterface $booking): void
    {
        $duplicate = Booking::where('student_id', $booking->getStudent()->id)
            ->where('stream_id', $booking->getStreamId())
            ->where('schedule_timeslot_id', $booking->getSlotId())
            ->exists();

        if ($duplicate) {
            throw new AlreadyExistsException('Selected time slot is not available.');
        }
    }
}
