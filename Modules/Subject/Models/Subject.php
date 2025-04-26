<?php

namespace Modules\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\LanguageLevel\Models\LanguageLevel;

class Subject extends Model
{
    protected $fillable = [
        'language_level_id',
        'title',
        'description',
    ];

    public function languageLevel()
    {
        return $this->belongsTo(LanguageLevel::class);
    }
}
