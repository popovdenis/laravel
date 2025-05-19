<?php

namespace Modules\LanguageLevel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Models\BookingScheduleManager;
use Modules\Booking\Services\BookingSlotService;
use Modules\LanguageLevel\Models\LanguageLevel;

class LanguageLevelController extends Controller
{
    private BookingScheduleManager $bookingScheduleManager;
    private BookingSlotService     $bookingSlotService;

    public function __construct(
        CustomerTimezone $timezone,
        BookingScheduleManager $bookingScheduleManager,
        BookingSlotService $bookingSlotService
    )
    {
        parent::__construct($timezone);
        $this->bookingScheduleManager = $bookingScheduleManager;
        $this->bookingSlotService = $bookingSlotService;
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

    public function index(Request $request)
    {
        $lessonType = $this->bookingSlotService->getDefaultLessonType();
        $visibleDatesCount = $this->bookingSlotService->getInitialDaysToShow();
        $startPreferredTime = $request->user()->getAttribute('preferred_start_time')?->format('H:i') ?? '00:00';
        $endPreferredTime = $request->user()->getAttribute('preferred_end_time')?->format('H:i') ?? '23:59';

        return view(
            'languagelevel::index',
            compact('lessonType', 'visibleDatesCount', 'startPreferredTime', 'endPreferredTime')
        );
    }

    public function init(Request $request)
    {
        $slotsResponse = $this->bookingScheduleManager
            ->setFilters($this->getFilters($request))
            ->setStudent($request->user())
            ->getBookingScheduleSlots();

        return response()->json($slotsResponse);
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
