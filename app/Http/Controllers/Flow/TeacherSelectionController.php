<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class TeacherSelectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        session(['course_id' => $request->course_id]);

        return redirect()->route('flow.selectTeacher.index');
    }

    public function index()
    {
        $courseId = session('course_id');

        if (! $courseId) {
            return redirect()->route('home')->with('error', 'No course selected.');
        }

        $course = Course::with('teachers')->findOrFail($courseId);

        return view('flow.select-teacher', [
            'course' => $course,
            'teachers' => $course->teachers,
        ]);
    }
}
