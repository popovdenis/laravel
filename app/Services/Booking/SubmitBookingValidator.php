<?php
declare(strict_types=1);

namespace App\Services\Booking;

use App\Exceptions\AlreadyExistsException;
use App\Models\Booking;
use App\Models\Booking\BookingInterface;

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
