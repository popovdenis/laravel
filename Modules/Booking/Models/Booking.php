<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Booking\Contracts\BookingInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Models\Enums\PaymentMethod;
use Modules\Payment\Services\PaymentMethodResolver;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;
use Modules\Stream\Models\Stream;
use Modules\User\Models\User;

class Booking extends Model implements BookingInterface
{
    protected $fillable = [
        'student_id',
        'stream_id',
        'schedule_timeslot_id',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    public function timeslot(): BelongsTo
    {
        return $this->belongsTo(ScheduleTimeslot::class, 'schedule_timeslot_id');
    }

    public function setStreamId(int $streamId): BookingInterface
    {
        $this->stream_id = $streamId;

        return $this;
    }

    public function getStreamId(): int
    {
        return $this->stream_id;
    }

    public function setSlotId(int $slotId): BookingInterface
    {
        $this->slot_id = $slotId;

        return $this;
    }

    public function getSlotId(): int
    {
        return $this->slot_id;
    }

    public function setStudent(User $student): BookingInterface
    {
        $this->student = $student;

        return $this;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): BookingInterface
    {
        $this->payment_method = $paymentMethod;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->payment_method;
    }

    public function setPayment(PaymentMethodInterface $payment): BookingInterface
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPayment(): PaymentMethodInterface
    {
        $payment = null;
        $paymentMethod = $this->getPaymentMethod();

        if ($paymentMethod === null) {
            // TODO: by default is credits. May be changed in the configuration to switch to Stripe.
            // TODO: if ID is present, then try to find a booking credit history
            $this->setPaymentMethod(PaymentMethod::CREDITS);

            /** @var PaymentMethodResolver $paymentMethodResolver */
            $paymentMethodResolver = app(PaymentMethodResolver::class);
            $payment = $paymentMethodResolver->resolve($this->getPaymentMethod(), $this);
            $this->setPayment($payment);
        }

        if ($payment) {
            $payment->setBooking($this);
        }

        return $payment;
    }

    public function setTransactionId(int $transactionId): BookingInterface
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    /**
     * Place order
     *
     * @return $this
     */
    public function place()
    {
//        $this->_eventManager->dispatch('sales_order_place_before', ['order' => $this]);
        $this->_placePayment();
//        $this->_eventManager->dispatch('sales_order_place_after', ['order' => $this]);
        return $this;
    }

    /**
     * Place order payments
     *
     * @return $this
     */
    protected function _placePayment()
    {
        $this->getPayment()->place();
        return $this;
    }

    public function canCancel()
    {
        // TODO: check the time allowed to cancel the booking before the meeting
        return true;
    }

    /**
     * Cancel order
     *
     * @return $this
     */
    public function cancel()
    {
        if ($this->canCancel()) {
            $this->getPayment()->cancel(); // refund the credits to the customer
//            $this->_eventManager->dispatch('order_cancel_after', ['order' => $this]);
        }

        return $this;
    }
}
