<?php

namespace Modules\ScheduleTimeslot\Models;

use App\Models\CourseEnrollment;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;
use Modules\User\Models\User;

class ScheduleTimeslot extends Model
{
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function enrollments()
    {
        return $this->belongsToMany(CourseEnrollment::class, 'course_enrollment_timeslots');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getParticipantsCountAttribute(): int
    {
        return $this->bookings
            ->groupBy('lesson_type')
            ->reduce(function ($count, $group) {
                return $count + ($group->first()->type === BookingTypeEnum::BOOKING_TYPE_GROUP ? 1 : count($group));
            }, 0);
    }
}
