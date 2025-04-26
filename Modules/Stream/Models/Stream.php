<?php

namespace Modules\Stream\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\LanguageLevel\Models\LanguageLevel;
use Modules\Stream\Models\Enums\StreamStatus;

class Stream extends Model
{
    protected $fillable = [
        'language_level_id',
        'teacher_id',
        'status',
        'start_date',
        'end_date',
        'current_subject_id',
        'current_subject_number',
        'repeat',
    ];

    protected $casts = [
        'status' => StreamStatus::class,
    ];

    public function languageLevel(): BelongsTo
    {
        return $this->belongsTo(LanguageLevel::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function currentSubject()
    {
        return $this->belongsTo(Subject::class, 'current_subject_id');
    }

    public function getCurrentSubjectNumberAttribute(): ?int
    {
        if (! $this->current_subject_id || ! $this->languageLevel) {
            return null;
        }

        $subjects = $this->languageLevel->subjects()
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        return array_search($this->current_subject_id, $subjects) !== false
            ? array_search($this->current_subject_id, $subjects) + 1
            : null;
    }
}
