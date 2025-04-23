<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevel;
use App\Models\Stream;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Уровни для дропдауна (только те, которые связаны с выбранными streams)
        $levels = $streams->pluck('languageLevel')->unique('id')->values();

        // Фильтруем потоки по выбранному уровню
        $filteredStreams = $selectedLevelId
            ? $streams->filter(fn($stream) => $stream->language_level_id == $selectedLevelId)
            : $streams;

        // Список subjects для выбранного уровня
        $subjects = null;
        if ($selectedLevelId) {
            $selectedLevel = $levels->where('id', $selectedLevelId)->first();
            $subjects = $selectedLevel?->subjects;
        }

        // Ограничиваем по дате (жёстко: текущая неделя)
        $today = Carbon::today();
        $weekAhead = $today->copy()->addDays(7);

        // Группируем слоты по дням
        $groupedSlots = [];
        foreach ($filteredStreams as $stream) {
            $subjectId = $stream->current_subject_id;

            // Фильтр по subject (если выбран)
            if (!empty($selectedSubjectIds) && !in_array($subjectId, $selectedSubjectIds)) {
                continue;
            }

            $streamStart = Carbon::parse($stream->start_date);
            $streamEnd = Carbon::parse($stream->end_date);

            $currentDate = $streamStart->copy();
            while ($currentDate->lte($streamEnd)) {
                $dayOfWeek = $currentDate->format('l'); // Monday, Tuesday, etc.

                // Слоты преподавателя на этот день недели
                $daySlots = $stream->teacher->scheduleTimeslots->filter(function ($slot) use ($dayOfWeek)
                {
                    return strtolower($slot->day) === strtolower($dayOfWeek);
                });

                foreach ($daySlots as $slot) {
                    $slotStart = $currentDate->copy()->setTimeFromTimeString($slot->start);

                    // Ограничиваем одной неделей (hardcode)
                    if ($slotStart->between($today, $weekAhead)) {
                        $dateKey = $slotStart->toDateString();
                        $groupedSlots[$dateKey][] = [
                            'time' => $slotStart->format('H:i A'),
                            'stream' => $stream,
                            'teacher' => $stream->teacher,
                            'subject' => $stream->currentSubject,
                            'current_subject_number' => $stream->current_subject_number,
                            'slot' => $slot,
                        ];
                    }
                }

                $currentDate->addDay();
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

//
//namespace App\Http\Controllers;
//
//use App\Models\LanguageLevel;
//use App\Models\Stream;
//use Illuminate\Http\Request;
//use Carbon\Carbon;
//
//class LanguageLevelController extends Controller
//{
//    public function index(Request $request)
//    {
//        $selectedLevelId = $request->input('level_id');
//        $selectedSubjectIds = $request->input('subject_ids', []);
//
//        // Получаем только streams со статусом planned или started
//        $streamsQuery = Stream::with([
//            'languageLevel.subjects',
//            'teacher.scheduleTimeslots',
//            'currentSubject',
//            'teacher',
//        ])->whereIn('status', ['planned', 'started']);
//
//        $streams = $streamsQuery->get();
//
//        // Уровни для дропдауна (берём только те, которые связаны с выбранными streams)
//        $levels = $streams->pluck('languageLevel')->unique('id')->values();
//
//        // Фильтруем потоки по выбранному уровню
//        $filteredStreams = $selectedLevelId
//            ? $streams->filter(fn($stream) => $stream->language_level_id == $selectedLevelId)
//            : $streams;
//
//        // Список subjects для выбранного уровня
//        $subjects = null;
//        if ($selectedLevelId) {
//            $selectedLevel = $levels->where('id', $selectedLevelId)->first();
//            $subjects = $selectedLevel?->subjects;
//        }
//
//        // Ограничение по неделе (hardcode)
//        $today = Carbon::today();
//        $weekAhead = $today->copy()->addDays(7);
//
//        // Группируем слоты по дням
//        $groupedSlots = [];
//        foreach ($filteredStreams as $stream) {
//            $subjectId = $stream->current_subject_id;
//
//            // Фильтр по subject (если выбран)
//            if (!empty($selectedSubjectIds) && !in_array($subjectId, $selectedSubjectIds)) {
//                continue;
//            }
//
//            $streamStart = Carbon::parse($stream->start_date);
//            $streamEnd = Carbon::parse($stream->end_date);
//
//            $currentDate = $streamStart->copy();
//            while ($currentDate->lte($streamEnd)) {
//                $dayOfWeek = $currentDate->format('l'); // Monday, Tuesday, etc.
//
//                // Слоты преподавателя на этот день недели
//                $daySlots = $stream->teacher->scheduleTimeslots->filter(function ($slot) use ($dayOfWeek)
//                {
//                    return strtolower($slot->day) === strtolower($dayOfWeek);
//                });
//
//                foreach ($daySlots as $slot) {
//                    $slotStart = $currentDate->copy()->setTimeFromTimeString($slot->start);
//
//                    // Ограничиваем одной неделей (hardcode)
//                    if ($slotStart->between($today, $weekAhead)) {
//                        $dateKey = $slotStart->toDateString();
//                        $groupedSlots[$dateKey][] = [
//                            'time' => $slotStart->format('H:i A'),
//                            'stream' => $stream,
//                            'teacher' => $stream->teacher,
//                            'subject' => $stream->currentSubject,
//                            'current_subject_number' => $stream->current_subject_number,
//                            'slot' => $slot,
//                        ];
//                    }
//                }
//
//                $currentDate->addDay();
//            }
//        }
//        ksort($groupedSlots);
//
//        return view('levels.index', [
//            'levels' => $levels,
//            'subjects' => $subjects,
//            'groupedSlots' => $groupedSlots,
//            'selectedLevelId' => $selectedLevelId,
//            'selectedSubjectIds' => $selectedSubjectIds,
//        ]);
//    }
//
//    public function show(string $slug)
//    {
//        $level = LanguageLevel::where('slug', $slug)
//            ->where('is_active', true)
//            ->firstOrFail();
//
//        return view('levels.show', compact('level'));
//    }
//}
