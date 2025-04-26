<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LanguageLevel\Models\LanguageLevel;

class TeacherSelectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:language_levels,id',
        ]);

        session(['level_id' => $request->level_id]);

        return redirect()->route('flow.selectTeacher.index');
    }

    public function index()
    {
        $courseId = session('level_id');

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
