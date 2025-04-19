<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'course_id',
    ];

    protected $casts = [
        'timeslot_ids' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function timeslots()
    {
        return $this->hasMany(CourseEnrollmentTimeslot::class);
    }

    public static function enrollWithTimeslots(int $userId, int $courseId, int $teacherId, array $slotIds): self
    {
        return DB::transaction(function () use ($userId, $courseId, $teacherId, $slotIds) {
            $enrollment = self::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'teacher_id' => $teacherId,
            ]);

            foreach ($slotIds as $slotId) {
                $enrollment->timeslots()->create([
                    'schedule_timeslot_id' => $slotId,
                ]);
            }

            return $enrollment;
        });
    }
}
