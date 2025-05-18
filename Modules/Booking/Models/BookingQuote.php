<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\CreditBalanceValidatorInterface;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Contracts\SubmitQuoteValidatorInterface;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Order\Models\Quote;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;
use Modules\User\Models\User;

/**
 * Class BookingQuote
 *
 * @package Modules\Booking\Models
 */
class BookingQuote extends Quote implements BookingQuoteInterface
{
    protected User                          $student;
    protected int                           $streamId;
    protected int                           $slotId;
    protected ScheduleTimeslot              $slot;
    protected SlotContext                   $slotContext;
    protected BookingTypeEnum               $lessonType;
    protected int                           $credits;
    private SubmitQuoteValidatorInterface   $bookingValidator;
    private CreditBalanceValidatorInterface $creditBalanceValidator;
    private SlotValidator                   $slotValidator;

    public function __construct(
        SubmitQuoteValidatorInterface   $bookingValidator,
        CreditBalanceValidatorInterface $creditBalanceValidator,
        SlotValidator                   $slotValidator
    )
    {
        $this->bookingValidator       = $bookingValidator;
        $this->creditBalanceValidator = $creditBalanceValidator;
        $this->slotValidator          = $slotValidator;
    }

    public function getPaymentMethodConfig(): string
    {
        return setting(Booking::PAYMENT_METHOD_CONFIG_PATH);
    }

    /**
     * @throws BookingValidationException
     */
    public function validate(): void
    {
        $this->creditBalanceValidator->validate($this);
        $this->bookingValidator->validate($this);
        $this->slotValidator->validate($this->getSlotContext());
    }

    public function save(): Booking
    {
        return Booking::create([
            'student_id'           => $this->getUser()->id,
            'stream_id'            => $this->getStreamId(),
            'schedule_timeslot_id' => $this->getSlot()->id,
            'slot_start_at'        => $this->getSlot()->getAttribute('start_time'),
            'slot_end_at'          => $this->getSlot()->getAttribute('end_time'),
            'lesson_type'          => $this->getLessonType(),
        ]);
    }

    public function setUser(User $user)
    {
        $this->student = $user;
    }

    public function getUser(): User
    {
        return $this->student;
    }

    public function setStreamId(int $streamId)
    {
        $this->streamId = $streamId;
    }

    public function getStreamId()
    {
        return $this->streamId;
    }

    public function getSlot()
    {
        return $this->slot;
    }

    public function setSlot(ScheduleTimeslot $slot)
    {
        $this->slot = $slot;
    }

    public function getSlotContext()
    {
        return $this->slotContext;
    }

    public function setSlotContext(SlotContextInterface $slotContext)
    {
        $this->slotContext = $slotContext;
    }

    public function setLessonType(BookingTypeEnum $lessonType)
    {
        $this->lessonType = $lessonType;
    }

    public function getLessonType()
    {
        return $this->lessonType;
    }

    public function getAmount()
    {
        return $this->credits;
    }

    public function setAmount(int $amount)
    {
        $this->credits = $amount;
    }

    public function getDescription()
    {
        return "Booking for timeslot ID {$this->slotId}";
    }

    public function getSourceType()
    {
        return Booking::class;
    }

    public function setSourceId(int $sourceId)
    {
        $this->slotId = $sourceId;
    }

    public function getSourceId()
    {
        return $this->slotId;
    }
}
