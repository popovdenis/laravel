<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
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
}
