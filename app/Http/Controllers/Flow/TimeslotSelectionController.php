<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        $courseId = session('level_id');

        if (! $teacherId || ! $courseId) {
            return redirect()->route('flow.selectTeacher.index')->with('error', 'No teacher or course selected.');
        }

        $teacher = User::with('scheduleTimeslots')->findOrFail($teacherId);
        $course = \Modules\LanguageLevel\Models\LanguageLevel::findOrFail($courseId);

        return view('flow.select-timeslot', [
            'teacher' => $teacher,
            'course' => $course,
            'timeslots' => $teacher->scheduleTimeslots,
        ]);
    }
}
