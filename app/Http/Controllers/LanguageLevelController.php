<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevel;
use App\Models\Stream;
use Illuminate\Http\Request;

class LanguageLevelController extends Controller
{
    public function index(Request $request)
    {
        $streams = Stream::with([
            'languageLevel.subjects',
            'teacher.scheduleTimeslots',
            'currentSubject',
            'teacher'
        ])
            ->whereIn('status', ['planned', 'started'])
            ->orderBy('start_date')
            ->get();

        $levels = $streams->pluck('languageLevel')->unique('id')->values();
        $selectedLevelId = $request->input('level_id') ?? optional($levels->first())->id;
        $selectedSubjectIds = $request->input('subject_ids', []);

        // List of subjects
        $subjects = $levels->firstWhere('id', $selectedLevelId)?->subjects ?? collect();

        $filteredStreams = $streams->filter(function ($stream) use ($selectedLevelId, $selectedSubjectIds) {
            $matchesLevel = $stream->language_level_id == $selectedLevelId;

            $matchesSubject = empty($selectedSubjectIds)
                || in_array($stream->current_subject_id, $selectedSubjectIds);

            return $matchesLevel && $matchesSubject;
        });

        return view('levels.index', [
            'levels'              => $levels,
            'streams'             => $filteredStreams,
            'selectedLevelId'     => $selectedLevelId,
            'selectedSubjectIds'  => $selectedSubjectIds,
            'subjects'            => $subjects,
        ]);
    }

    public function show(string $slug)
    {
        $level = LanguageLevel::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('levels.show', compact('level'));
    }
}
