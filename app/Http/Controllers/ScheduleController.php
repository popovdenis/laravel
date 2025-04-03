<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::whereHas('students', function ($query) {
            $query->where('student_id', Auth::id());
        })->with('teacher')->orderBy('start_time')->get();

        return view('schedule.index', compact('schedules'));
    }
}
