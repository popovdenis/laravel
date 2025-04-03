<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\RedirectResponse;
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

    public function join(Schedule $schedule): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasRole('Teacher')) {
            $url = app(ZoomService::class)->getStartUrl($schedule);
        } elseif ($user->hasRole('Student')) {
            $url = app(ZoomService::class)->getJoinUrl($schedule);
        } else {
            abort(403);
        }

        return redirect()->away($url);
    }
}
