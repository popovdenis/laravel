<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LanguageLevel;

class TeacherSelectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:language_levels,id',
        ]);

        session(['course_id' => $request->course_id]);

        return redirect()->route('flow.selectTeacher.index');
    }

    public function index()
    {
        $courseId = session('course_id');

        if (! $courseId) {
            return redirect()->route('dashboard')->with('error', 'No course selected.');
        }

        $course = LanguageLevel::with('teachers')->findOrFail($courseId);

        return view('flow.select-teacher', [
            'course' => $course,
            'teachers' => $course->teachers,
        ]);
    }
}
