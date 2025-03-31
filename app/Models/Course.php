<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Binafy\LaravelCart\Cartable;

class Course extends Model implements Cartable
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
