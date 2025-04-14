<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'start_time',
        'end_time',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'custom_link',
        'passcode',
        'notify_user',
        'user_notified',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'schedule_student', 'schedule_id', 'student_id')
            ->withPivot(['notify_user', 'user_notified']);
    }

//    protected static function booted(): void
//    {
//        static::deleting(function ($project) {
//            Storage::disk('public')->delete($project->thumbnail);
//        });
//    }
}
