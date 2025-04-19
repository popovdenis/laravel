<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TimeslotSelectionController extends Controller
{
    public function store(Request $request)
    {
        $teacherId = session('selected_teacher_id');

        if (! $teacherId) {
            return redirect()->route('flow.selectTeacher.index')->with('error', 'No teacher selected.');
        }

        $request->validate([
            'selected_slots' => 'required|json',
        ]);

        session(['selected_timeslot_ids' => json_decode($request->selected_slots, true)]);

        return redirect()->route('flow.checkout.show');
    }

    public function index()
    {
        $teacherId = session('selected_teacher_id');
        $courseId = session('selected_course_id');

        if (! $teacherId || ! $courseId) {
            return redirect()->route('flow.selectTeacher.index')->with('error', 'No teacher or course selected.');
        }

        $teacher = User::with('scheduleTimeslots')->findOrFail($teacherId);
        $course = \App\Models\Course::findOrFail($courseId);

        return view('flow.select-timeslot', [
            'teacher' => $teacher,
            'course' => $course,
            'timeslots' => $teacher->scheduleTimeslots,
        ]);
    }
}
