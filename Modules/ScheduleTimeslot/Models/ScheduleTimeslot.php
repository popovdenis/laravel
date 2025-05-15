<?php

namespace Modules\ScheduleTimeslot\Models;

use App\Models\CourseEnrollment;
use Illuminate\Database\Eloquent\Model;

class ScheduleTimeslot extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'start',
        'end',
    ];

    protected ?string $slotStartAt;

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

    public function setSlotStartAtAttribute($value)
    {
        $this->slotStartAt = $value;
    }

    public function getSlotStartAtAttribute()
    {
        return $this->slotStartAt;
    }
}
