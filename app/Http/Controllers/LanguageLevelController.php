<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevel;

class LanguageLevelController extends Controller
{
    public function index()
    {
        $levels = \App\Models\LanguageLevel::with(['subjects', 'teachers.scheduleTimeslots'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('levels.index', compact('levels'));
    }

    public function show(string $slug)
    {
        $level = LanguageLevel::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('levels.show', compact('level'));
    }
}
