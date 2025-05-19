<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Modules\Base\Exceptions\AlreadyExistsException;
use Modules\Booking\Contracts\SubmitQuoteValidatorInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Order\Contracts\QuoteInterface;

/**
 * Class SubmitBookingValidator
 *
 * @package App\Services\Booking
 */
class SubmitBookingValidator implements SubmitQuoteValidatorInterface
{
    public function validate(QuoteInterface $bookingQuote): void
    {
        $slot = $bookingQuote->getSlot();

        $duplicate = Booking::where('student_id', $bookingQuote->getStudent()->id)
            ->where('teacher_id', $bookingQuote->getTeacher()->id)
            ->where('stream_id', $bookingQuote->getStreamId())
            ->where('slot_start_at', $slot->start_time)
            ->where('slot_end_at', $slot->end_time)
            ->where('status', '!=', BookingStatus::CANCELLED)
            ->exists();

        if ($duplicate) {
            throw new AlreadyExistsException('Selected time slot is not available.');
        }
    }
}
