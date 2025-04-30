<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use App\Exceptions\AlreadyExistsException;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\SubmitBookingValidatorInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;

/**
 * Class SubmitBookingValidator
 *
 * @package App\Services\Booking
 */
class SubmitBookingValidator implements SubmitBookingValidatorInterface
{
    public function validate(BookingQuoteInterface $bookingQuote): void
    {
        $duplicate = Booking::where('student_id', $bookingQuote->getUser()->id)
            ->where('stream_id', $bookingQuote->getStreamId())
            ->where('schedule_timeslot_id', $bookingQuote->getSlotId())
            ->where('status', '!=', BookingStatus::CANCELLED)
            ->exists();

        if ($duplicate) {
            throw new AlreadyExistsException('Selected time slot is not available.');
        }
    }
}
