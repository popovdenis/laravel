<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Order\Models\Order;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;
use Modules\Stream\Models\Stream;
use Modules\Subject\Models\Subject;
use Modules\User\Models\User;

class Booking extends Model implements PurchasableInterface
{
    const PAYMENT_METHOD_CONFIG_PATH = 'booking.applicable_payment_method';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'stream_id',
        'schedule_timeslot_id',
        'current_subject_id',
        'status',
        'lesson_type',
        'slot_start_at',
        'slot_end_at',
    ];
    protected $casts = [
        'slot_start_at' => 'datetime',
        'slot_end_at' => 'datetime',
        'lesson_type'   => BookingTypeEnum::class,
    ];

    public function payment(): MorphOne
    {
        return $this->morphOne(Order::class, 'purchasable');
    }

    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'purchasable');
    }

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

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'current_subject_id');
    }

    public function markAsPending(): void
    {
        $this->update(['status' => BookingStatus::PENDING]);
    }

    public function markAsConfirmed(): void
    {
        $this->update(['status' => BookingStatus::CONFIRMED]);
    }

    public function markAsCancelled(): void
    {
        $this->update(['status' => BookingStatus::CANCELLED]);
    }

    public function getPaymentMethod(): string
    {
        return setting(self::PAYMENT_METHOD_CONFIG_PATH);
    }
}
