<?php

namespace Modules\Booking\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payment\Services\PaymentMethodInterface;
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
        return $this;
    }

    public function setPayment(PaymentMethodInterface $payment): BookingInterface
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPayment(): PaymentMethodInterface
    {
        return $this->payment;
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }
}
