<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ZoomService;
use Illuminate\Http\RedirectResponse;

class ZoomMeetingController extends Controller
{
    public function create(Schedule $schedule, ZoomService $zoomService): RedirectResponse
    {
        $this->authorize('update', $schedule);

        $zoomService->createMeeting($schedule);

        return back()->with('success', 'Meeting created successfully.');
    }
}
