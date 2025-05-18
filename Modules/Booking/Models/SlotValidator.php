<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Models\Validator\BookingTypeValidator;
use Modules\Booking\Models\Validator\MinimumAdvanceTimeValidator;
use Modules\Booking\Models\Validator\PassStudentTimeValidator;
use Modules\Booking\Models\Validator\TeacherAvailabilityValidator;

/**
 * Class BookingValidator
 *
 * @package Modules\Booking\Models
 */
class SlotValidator
{
    private TeacherAvailabilityValidator $teacherValidator;
    private PassStudentTimeValidator     $studentTimeValidator;
    private MinimumAdvanceTimeValidator  $advanceTimeValidator;
    private BookingTypeValidator         $bookingTypeValidator;

    public function __construct(
        TeacherAvailabilityValidator $teacherValidator,
        PassStudentTimeValidator $studentTimeValidator,
        MinimumAdvanceTimeValidator $advanceTimeValidator,
        BookingTypeValidator $bookingTypeValidator,
    )
    {
        $this->teacherValidator = $teacherValidator;
        $this->studentTimeValidator = $studentTimeValidator;
        $this->advanceTimeValidator = $advanceTimeValidator;
        $this->bookingTypeValidator = $bookingTypeValidator;
    }

    /**
     * @throws BookingValidationException
     */
    public function validate(SlotContextInterface $slotContext): void
    {
        $this->teacherValidator->validate($slotContext);
        $this->studentTimeValidator->validate($slotContext);
        $this->advanceTimeValidator->validate($slotContext);
        $this->bookingTypeValidator->validate($slotContext);
    }
}