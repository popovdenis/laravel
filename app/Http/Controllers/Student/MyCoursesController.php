<?php

namespace App\Http\Controllers\Student;

use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;

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
