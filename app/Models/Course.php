<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'duration',
        'price',
    ];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher');
    }
}
