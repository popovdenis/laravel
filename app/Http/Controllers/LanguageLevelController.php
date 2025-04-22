<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\LanguageLevel;

class LanguageLevelController extends Controller
{
    public function index()
    {
        $streams = Stream::with([
            'languageLevel',
            'teacher.scheduleTimeslots',
            'languageLevel.subjects',
        ])
            ->whereIn('status', ['planned', 'started'])
            ->orderBy('start_date')
            ->get();

        return view('levels.index', compact('streams'));
    }

    public function show(string $slug)
    {
        $level = LanguageLevel::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('levels.show', compact('level'));
    }
}
