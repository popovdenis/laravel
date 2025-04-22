<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEnrollment;

class ConfirmationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $teacherId = session('teacher_id');
            $slotIds = session('slot_id');
            $courseId = session('level_id');

            if (! $teacherId || ! $slotIds || ! $courseId) {
                return redirect()->route('levels.index')->with('error', 'Missing enrollment data.');
            }

            if (!auth()->user()) {
                return redirect()->route('login')->with('error', 'You should be logged in first.');
            }

            CourseEnrollment::enrollWithTimeslots(
                auth()->id(),
                $courseId,
                $teacherId,
                (array) $slotIds
            );

            session()->forget(['teacher_id', 'slot_id', 'level_id']);

            return redirect()->route('checkout.confirmed')->with('success', 'Enrollment successful!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('levels.index')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function confirmed()
    {
        return view('flow.confirmed');
    }
}
