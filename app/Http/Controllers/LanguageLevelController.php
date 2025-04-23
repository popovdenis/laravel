<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevel;
use App\Models\Stream;
use Illuminate\Http\Request;

class LanguageLevelController extends Controller
{
    public function index(Request $request)
    {
        $selectedLevelId = $request->input('level_id');
        $selectedSubjectIds = $request->input('subject_ids', []);

        // Получаем только streams со статусом planned или started
        $streamsQuery = Stream::with([
            'languageLevel.subjects',
            'teacher.scheduleTimeslots',
            'currentSubject',
            'teacher',
        ])->whereIn('status', ['planned', 'started']);

        $streams = $streamsQuery->get();

        // Уровни для дропдауна (берём только те, которые связаны с выбранными streams)
        $levels = $streams->pluck('languageLevel')->unique('id')->values();

        // Если выбран level, фильтруем потоки по этому уровню
        $filteredStreams = $selectedLevelId
            ? $streams->filter(fn($stream) => $stream->language_level_id == $selectedLevelId)
            : $streams;

        // Собираем subjects выбранного level для сайдбара
        $subjects = null;
        if ($selectedLevelId) {
            $selectedLevel = $levels->where('id', $selectedLevelId)->first();
            $subjects = $selectedLevel?->subjects;
        }

        // Группируем слоты по дням
        $groupedSlots = [];
        foreach ($filteredStreams as $stream) {
            $subjectId = $stream->current_subject_id;

            // Фильтр по subject, если выбран
            if (!empty($selectedSubjectIds) && !in_array($subjectId, $selectedSubjectIds)) {
                continue;
            }

            foreach ($stream->teacher->scheduleTimeslots as $slot) {
                $dateKey = \Carbon\Carbon::parse($slot->start)->toDateString();
                $groupedSlots[$dateKey][] = [
                    'time'                     => \Carbon\Carbon::parse($slot->start)->format('H:i A'),
                    'stream'                   => $stream,
                    'teacher'                  => $stream->teacher,
                    'subject'                  => $stream->currentSubject,
                    'current_subject_number'   => $stream->current_subject_number,
                    'slot'                     => $slot,
                ];
            }
        }
        ksort($groupedSlots);

        return view('levels.index', [
            'levels'             => $levels,
            'subjects'           => $subjects,
            'groupedSlots'       => $groupedSlots,
            'selectedLevelId'    => $selectedLevelId,
            'selectedSubjectIds' => $selectedSubjectIds,
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
