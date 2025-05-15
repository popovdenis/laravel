<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\CreditBalanceValidatorInterface;
use Modules\Booking\Contracts\SlotAvailabilityValidatorInterface;
use Modules\Booking\Contracts\SubmitQuoteValidatorInterface;
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
    protected User $student;
    protected int $streamId;
    protected int $slotId;
    protected ScheduleTimeslot $slot;
    protected int $credits;
    private SubmitQuoteValidatorInterface $bookingValidator;
    private SlotAvailabilityValidatorInterface $slotValidator;
    private CreditBalanceValidatorInterface $creditBalanceValidator;

    public function __construct(
        SubmitQuoteValidatorInterface      $bookingValidator,
        SlotAvailabilityValidatorInterface $slotValidator,
        CreditBalanceValidatorInterface    $creditBalanceValidator,
    )
    {
        $this->bookingValidator = $bookingValidator;
        $this->slotValidator = $slotValidator;
        $this->creditBalanceValidator = $creditBalanceValidator;
    }

    public function getPaymentMethodConfig(): string
    {
        return setting(Booking::PAYMENT_METHOD_CONFIG_PATH);
    }

    public function validate(): void
    {
        $this->creditBalanceValidator->validate($this);
        $this->bookingValidator->validate($this);
        $this->slotValidator->validate($this);
    }

    public function save(): Booking
    {
        return Booking::create([
            'student_id' => $this->getUser()->id,
            'stream_id' => $this->getStreamId(),
            'schedule_timeslot_id' => $this->getSlot()->id,
            'slot_start_at' => $this->getSlot()->getSlotStartAtAttribute(),
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
