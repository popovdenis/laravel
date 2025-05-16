<?php

namespace Modules\LanguageLevel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\LanguageLevel\Models\LanguageLevel;
use Modules\LanguageLevel\Services\CatalogSlotsListService;

class LanguageLevelController extends Controller
{
    private CatalogSlotsListService $catalogSlotsListService;

    public function __construct(
        CustomerTimezone $timezone,
        CatalogSlotsListService  $catalogSlotsListService,
    )
    {
        parent::__construct($timezone);
        $this->catalogSlotsListService = $catalogSlotsListService;
    }

    private function getFilters(Request $request): array
    {
        return [
            'level_id' => $request->input('level_id'),
            'subject_ids' => $request->input('subject_ids', []),
            'type' => $request->input('type'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'lesson_type' => $request->input('lesson_type', 'group'),
        ];
    }

    public function index()
    {
        return view('languagelevel::index');
    }

    public function init(Request $request)
    {
        $user = $request->user();

        $filters = $this->getFilters($request);

        $streams = $this->catalogSlotsListService->getStreams();
        $levels = $this->catalogSlotsListService->getLevels($streams);
        $subjects = $this->catalogSlotsListService->getSubjects($filters['level_id'] ?? null, $levels);

        $filterStartDate = $this->catalogSlotsListService->getFilterStartDate($filters);
        $filterEndDate = $this->catalogSlotsListService->getFilterEndDate($filters);
        $groupedSlots = $this->catalogSlotsListService->groupSlots($filters, $filterStartDate, $filterEndDate, $user);

        return response()->json([
            'levels' => $levels,
            'subjects' => $subjects,
            'lessonType' => $filters['lesson_type'],
            'slots' => $groupedSlots,
            'selectedLevelId' => $filters['level_id'] ?? $levels->first()->id,
            'selectedSubjectIds' => $filters['subject_ids'],
            'filterStartDate' => $filterStartDate->toDateString(),
            'filterEndDate' => $filterEndDate->toDateString(),
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
