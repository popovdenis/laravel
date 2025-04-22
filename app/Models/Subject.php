<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
