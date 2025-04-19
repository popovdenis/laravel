<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Auth;

class ConfirmationController extends Controller
{
    public function store(Request $request)
    {
        $teacherId = session('teacher_id');
        $slotIds = session('slot_id');
        $courseId = session('course_id');

        if (! $teacherId || ! $slotIds || ! $courseId) {
            return redirect()->route('courses.index')->with('error', 'Missing enrollment data.');
        }

        CourseEnrollment::create([
            'user_id' => Auth::id(),
            'teacher_id' => $teacherId,
            'course_id' => $courseId,
            'timeslot_ids' => $slotIds,
        ]);

        // cleanup session
        session()->forget(['teacher_id', 'slot_id', 'course_id']);

        return redirect()->route('checkout.confirmed')->with('success', 'Enrollment successful!');
    }

    public function confirmed()
    {
        return view('flow.confirmed');
    }
}
