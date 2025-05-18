<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Illuminate\Support\Facades\Log;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Exceptions\BookingValidationException;

/**
 * Class TeacherAvailabilityValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class TeacherAvailabilityValidator
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
        $teacher = $slotContext->getTeacher();
        if (empty($teacher)) {
            Log::error('The teacher is not provided.');
            throw new BookingValidationException('The teacher is not provided.');
        }

        $teacherTzId = $teacher->timeZoneId;
        $slotStartTtz = $this->timezone->date($slotContext->getSlotStart(), $teacherTzId);
        $slotEndTtz = $this->timezone->date($slotContext->getSlotEnd(), $teacherTzId);

        $dayOfWeek = strtolower($slotStartTtz->format('l'));

        $available = $teacher->scheduleTimeslots->contains(
            function ($slot) use ($dayOfWeek, $slotStartTtz, $slotEndTtz, $teacherTzId) {
                $workStart = $this->timezone->date($slotStartTtz, $teacherTzId)->setTimeFrom($slot->start_time);
                $workEnd = $this->timezone->date($slotEndTtz, $teacherTzId)->setTimeFrom($slot->end_time);

                return $slot->day_of_week === $dayOfWeek &&
                    $slotStartTtz->between($workStart, $workEnd)
                    && $slotEndTtz->between($workStart, $workEnd);
        });

        if (! $available) {
            throw new BookingValidationException(sprintf(
                'The teacher %s %s is not available.', $teacher->firstname, $teacher->lastname
            ));
        }
    }
}