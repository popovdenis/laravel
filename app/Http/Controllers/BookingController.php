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
            'stream_id' => 'required|exists:streams,id',
            'timeslot_id' => 'required|exists:schedule_timeslots,id',
        ]);

        $studentId = Auth::id();

        // Check the duplicate
        $exists = Booking::where('student_id', $studentId)
            ->where('stream_id', $request->stream_id)
            ->where('schedule_timeslot_id', $request->timeslot_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already booked this lesson.');
        }

        Booking::create([
            'student_id'           => $studentId,
            'stream_id'            => $request->stream_id,
            'schedule_timeslot_id' => $request->timeslot_id,
            'status'               => BookingStatus::PENDING,
        ]);

        return back()->with('success', 'Booking successful!');
    }
}
