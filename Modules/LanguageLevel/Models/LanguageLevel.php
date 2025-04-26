<?php

namespace Modules\LanguageLevel\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Subject\Models\Subject;
use Modules\User\Models\User;

class LanguageLevel extends Model
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'language_level_teacher')
            ->withPivot('current_subject_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
