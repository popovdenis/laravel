<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Carbon\Carbon;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Exceptions\BookingValidationException;

/**
 * Class PassStudentTimeValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class PassStudentTimeValidator
{
    /**
     * @throws BookingValidationException
     */
    public function validate(SlotContextInterface $slotContext): void
    {
        $student = $slotContext->getStudent();
        $slotStart = $slotContext->getSlotStart();
        $slotEnd = $slotContext->getSlotEnd();

        $preferredStart = $student->preferred_start_time
            ? Carbon::createFromFormat('H:i', $student->preferred_start_time->format('H:i'), $student->timeZoneId)
            : null;
        $preferredEnd = $student->preferred_end_time
            ? Carbon::createFromFormat('H:i', $student->preferred_end_time->format('H:i'), $student->timeZoneId)
            : null;

        if ($preferredStart &&
            $preferredEnd &&
            $slotStart->between($preferredStart, $preferredEnd) &&
            $slotEnd->between($preferredStart, $preferredEnd)
        ) {
            throw new BookingValidationException('The slot is outside the student’s preferred time range.');
        }
    }
}