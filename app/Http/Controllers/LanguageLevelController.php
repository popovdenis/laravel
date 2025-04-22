<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevel;
use App\Models\Stream;
use Illuminate\Http\Request;

class LanguageLevelController extends Controller
{
    public function index(Request $request)
    {
        // Получаем только потоки со статусами planned или started
        $streams = Stream::with([
            'languageLevel.subjects',
            'teacher.scheduleTimeslots',
            'currentSubject',
            'teacher'
        ])
            ->whereIn('status', ['planned', 'started'])
            ->orderBy('start_date')
            ->get();

        // Для фильтра в сайдбаре: список уровней (levels)
        $levels = $streams->pluck('languageLevel')->unique('id')->values();

        $selectedLevelId = $request->input('level_id') ?? optional($levels->first())->id;

        // Фильтрация потоков по выбранному уровню (если выбран)
        $filteredStreams = $streams->filter(function ($stream) use ($selectedLevelId) {
            return $stream->language_level_id == $selectedLevelId;
        });

        return view('levels.index', [
            'levels'           => $levels,
            'streams'          => $filteredStreams,
            'selectedLevelId'  => $selectedLevelId,
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
