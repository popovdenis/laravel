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
