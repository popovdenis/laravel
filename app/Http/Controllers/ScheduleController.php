<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Student')) {
            $schedules = Schedule::whereHas('students', function ($query) {
                $query->where('student_id', Auth::id());
            })->with('teacher')->orderBy('start_time')->get();
        } elseif (auth()->user()->hasRole('Teacher')) {
            $schedules = Schedule::where('teacher_id', Auth::id())
                ->with('students')
                ->orderBy('start_time')
                ->get();
        }

        return view('schedule.index', compact('schedules'));
    }
}
