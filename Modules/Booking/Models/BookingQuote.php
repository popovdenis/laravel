<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\SlotAvailabilityValidatorInterface;
use Modules\Booking\Contracts\SubmitBookingValidatorInterface;
use Modules\Order\Models\Quote;
use Modules\Payment\Contracts\PaymentInterface;
use Modules\Payment\Models\Payment;
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
    protected int $credits;

    public function getPaymentMethodConfig(): string
    {
        return setting(Booking::PAYMENT_METHOD_CONFIG_PATH);
    }

    public function validate(): void
    {
        app(SubmitBookingValidatorInterface::class)->validate($this);
        app(SlotAvailabilityValidatorInterface::class)->validate($this);
    }

    public function save(): Booking
    {
        return Booking::create([
            'student_id' => $this->getUser()->id,
            'stream_id' => $this->getStreamId(),
            'schedule_timeslot_id' => $this->getSlotId(),
        ]);
    }

    public function setUser(User $user): void
    {
        $this->student = $user;
    }

    public function getUser(): User
    {
        return $this->student;
    }

    public function setStreamId(int $streamId): void
    {
        $this->streamId = $streamId;
    }

    public function getStreamId(): int
    {
        return $this->streamId;
    }

    public function getSlotId(): int
    {
        return $this->slotId;
    }

    public function setSlotId(int $slotId): void
    {
        $this->slotId = $slotId;
    }

    public function getAmount(): int
    {
        return $this->credits;
    }

    public function setAmount(int $amount): void
    {
        $this->credits = $amount;
    }

    public function getDescription(): string
    {
        return "Booking for timeslot ID {$this->slotId}";
    }

    public function getSourceType(): string
    {
        return Booking::class;
    }

    public function getSourceId(): int
    {
        return $this->slotId;
    }


}
