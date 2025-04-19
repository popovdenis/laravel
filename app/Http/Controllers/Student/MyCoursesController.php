<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseEnrollment;

class MyCoursesController extends Controller
{
    public function index()
    {
        $courses = CourseEnrollment::with([
            'course',
            'teacher',
            'timeslots.scheduleTimeslot',
        ])->where('user_id', Auth::id())->get();

        return view('student.my-courses', compact('courses'));
    }
}
