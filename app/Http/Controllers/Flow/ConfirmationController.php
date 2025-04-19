<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEnrollment;
use App\Models\CourseEnrollmentTimeslot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ConfirmationController extends Controller
{

    public function store(Request $request)
    {
        $user = auth()->user();

        $teacherId = session('teacher_id');
        $slotIds = session('slot_id');
        $courseId = session('course_id');

        if (! $teacherId || ! $slotIds || ! $courseId) {
            return redirect()->route('courses.index')->with('error', 'Missing enrollment data.');
        }

        DB::transaction(function () use ($user, $courseId, $teacherId, $slotIds) {
            $enrollment = CourseEnrollment::create([
                'user_id' => $user->id,
                'teacher_id' => $teacherId,
                'course_id' => $courseId,
            ]);

            foreach ((array) $slotIds as $slotId) {
                CourseEnrollmentTimeslot::create([
                    'course_enrollment_id' => $enrollment->id,
                    'schedule_timeslot_id' => $slotId,
                ]);
            }
        });

        // cleanup session
        session()->forget(['teacher_id', 'slot_id', 'course_id']);

        return redirect()->route('checkout.confirmed')->with('success', 'Enrollment successful!');
    }

    public function confirmed()
    {
        return view('flow.confirmed');
    }
}
