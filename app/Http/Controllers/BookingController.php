<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Enums\BookingStatus;
use App\Models\Stream;
use App\Models\ScheduleTimeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'stream_id'       => 'required|exists:streams,id',
            'selected_slots'  => 'required|json',
        ]);

        $slotIds = json_decode($request->selected_slots, true);
        $studentId = Auth::id();

        foreach ($slotIds as $slotId) {
            $exists = Booking::where('student_id', $studentId)
                ->where('stream_id', $request->stream_id)
                ->where('schedule_timeslot_id', $slotId)
                ->exists();

            if (! $exists) {
                Booking::create([
                    'student_id'           => $studentId,
                    'stream_id'            => $request->stream_id,
                    'schedule_timeslot_id' => $slotId,
                    'status'               => BookingStatus::PENDING,
                ]);
            }
        }

        return back()->with('success', 'Booking successful!');
    }
}
