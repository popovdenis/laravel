<?php

namespace Modules\LanguageLevel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Stream\Models\Stream;
use Carbon\Carbon;
use Modules\LanguageLevel\Models\LanguageLevel;

class LanguageLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        ])
            ->whereIn('status', ['planned', 'started'])
            ->whereHas('languageLevel', fn($q) => $q->where('is_active', true));

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

            // Subject filter, if selected
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
                            'time'                     => $slotStart->format('H:i'),
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

        foreach ($groupedSlots as $dateKey => &$slots) {
            usort($slots, function ($a, $b) {
                $timeA = \Carbon\Carbon::createFromFormat('H:i', $a['time']);
                $timeB = \Carbon\Carbon::createFromFormat('H:i', $b['time']);

                if ($timeA->eq($timeB)) {
                    return $a['current_subject_number'] <=> $b['current_subject_number'];
                }

                return $timeA->lt($timeB) ? -1 : 1;
            });
        }
        unset($slots);

        return view('languagelevel::index', [
            'levels'             => $levels,
            'subjects'           => $subjects,
            'groupedSlots'       => $groupedSlots,
            'selectedLevelId'    => $selectedLevelId,
            'selectedSubjectIds' => $selectedSubjectIds,
            'filterStartDate'    => $filterStartDate->toDateString(),
            'filterEndDate'      => $filterEndDate->toDateString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('languagelevel::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(string $slug)
    {
        $level = LanguageLevel::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('levels.show', compact('level'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('languagelevel::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
