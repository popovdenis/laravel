<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canManageBlogPosts(): bool
    {
        return $this->hasRole('Admin');
    }

    public function teachingCourses()
    {
        return $this->belongsToMany(Course::class, 'course_teacher');
    }

    public function scheduleTimeslots()
    {
        return $this->hasMany(\App\Models\ScheduleTimeslot::class, 'user_id');
    }

    public function getTimesheetAttribute(): array
    {
        return $this->scheduleTimeslots->map(function ($slot) {
            return [
                'day' => $slot->day,
                'start' => $slot->start,
                'end' => $slot->end,
            ];
        })->toArray();
    }
}
