<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTimeslot extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'start',
        'end',
    ];

    public function enrollments()
    {
        return $this->belongsToMany(CourseEnrollment::class, 'course_enrollment_timeslots');
    }

    public function getStartAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    public function getEndAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }
}
