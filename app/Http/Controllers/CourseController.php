<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('courses.index', compact('courses'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('courses.show', compact('course'));
    }
}
