<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Binafy\LaravelCart\Cartable;

class LanguageLevel extends Model implements Cartable
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
        return $this->belongsToMany(User::class, 'language_level_teacher');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }
}
