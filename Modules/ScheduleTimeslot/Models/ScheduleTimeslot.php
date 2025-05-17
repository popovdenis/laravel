<?php

namespace Modules\ScheduleTimeslot\Models;

use App\Models\CourseEnrollment;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;

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

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_timeslot_id');
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

    public function getParticipantsCountAttribute(): int
    {
        return $this->bookings
            ->groupBy('lesson_type')
            ->reduce(function ($count, $group) {
                return $count + ($group->first()->type === BookingTypeEnum::BOOKING_TYPE_GROUP ? 1 : count($group));
            }, 0);
    }
}
