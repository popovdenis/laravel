<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Carbon\Carbon;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Exceptions\BookingValidationException;

/**
 * Class PassStudentTimeValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class PassStudentTimeValidator
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
        $slotStartTime = $slotContext->getSlotStart()->format('H:i');
        $slotEndTime = $slotContext->getSlotEnd()->format('H:i');
        $student = $slotContext->getStudent();

        $preferredStart = $student->preferred_start_time?->format('H:i');
        $preferredEnd = $student->preferred_end_time?->format('H:i');

        if ($preferredStart && $preferredEnd &&
            ($slotStartTime < $preferredStart || $slotEndTime > $preferredEnd)) {
            throw new BookingValidationException('The slot is outside the student’s preferred time range.');
        }
    }
}