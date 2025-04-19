<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TimeslotSelectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        session(['teacher_id' => $request->teacher_id]);

        return redirect()->route('flow.selectTimeslot.index');
    }

    public function index()
    {
        $teacherId = session('teacher_id');
        $courseId = session('course_id');

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
