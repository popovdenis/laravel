<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollmentTimeslot extends Model
{
    protected $fillable = [
        'course_enrollment_id',
        'schedule_timeslot_id',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class, 'course_enrollment_id');
    }

    public function timeslot(): BelongsTo
    {
        return $this->belongsTo(ScheduleTimeslot::class, 'schedule_timeslot_id');
    }

    public function scheduleTimeslot()
    {
        return $this->belongsTo(ScheduleTimeslot::class);
    }
}
