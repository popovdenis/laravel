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

        $filterStartDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::today();
        $filterEndDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::today()->copy()->addDays(7);

        // Get streams with planned and started statuses
        $streamsQuery = Stream::with([
            'languageLevel.subjects',
            'teacher.scheduleTimeslots',
            'currentSubject',
            'teacher',
        ])->whereIn('status', ['planned', 'started']);

        $streams = $streamsQuery->get();

        // Levels for the dropdown (only those which linked with the streams)
        $levels = $streams->pluck('languageLevel')->unique('id')->values();

        // Filter streams by the selected Level
        if (!$selectedLevelId && $levels->isNotEmpty()) {
            $selectedLevelId = $levels->first()->id;
        }

        $filteredStreams = $streams->filter(fn($stream) => $stream->language_level_id == $selectedLevelId);

        // List of subjects for the selected level
        $subjects = null;
        if ($selectedLevelId) {
            $selectedLevel = $levels->where('id', $selectedLevelId)->first();
            $subjects = $selectedLevel?->subjects;
        }

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

                // Teacher's slots on the day
                $daySlots = $stream->teacher->scheduleTimeslots->filter(function ($slot) use ($dayOfWeek) {
                    return strtolower($slot->day) === strtolower($dayOfWeek);
                });

                foreach ($daySlots as $slot) {
                    $slotStart = $currentDate->copy()->setTimeFromTimeString($slot->start);

                    // Restrict by the filtered dates
                    if ($slotStart->between($filterStartDate, $filterEndDate)) {
                        $dateKey = $slotStart->toDateString();
                        $groupedSlots[$dateKey][] = [
                            'time'                     => $slotStart->format('H:i A'),
                            'stream'                   => $stream,
                            'teacher'                  => $stream->teacher,
                            'subject'                  => $stream->currentSubject,
                            'current_subject_number'   => $stream->current_subject_number,
                            'slot'                     => $slot,
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
            'filterStartDate'    => $filterStartDate->toDateString(),
            'filterEndDate'      => $filterEndDate->toDateString(),
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
