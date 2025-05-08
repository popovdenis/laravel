<?php
declare(strict_types=1);

namespace Modules\BookingGridFlat\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\BookingGridFlat\Contracts\BookingGridFlatInterface;

class BookingGridFlat extends Model implements BookingGridFlatInterface
{
    protected $table = 'booking_grid_flat';

    protected $fillable = [
        'booking_id',
        'student_id',
        'student_fullname',
        'teacher_id',
        'teacher_fullname',
        'stream_id',
        'level_title',
        'subject_title',
        'current_subject_number',
//        'subject_category',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function markAsConfirmed(): void
    {
        $this->booking->markAsConfirmed();
        $this->update(['status' => BookingStatus::CONFIRMED]);
    }
}

