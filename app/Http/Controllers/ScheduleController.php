<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Student') || auth()->user()->hasRole('Teacher')) {
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

    public function join(Schedule $schedule)
    {
        $user = auth()->user();

        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');
        $role = $user->hasRole('Teacher') ? 1 : 0;

        $signature = ZoomService::generateSignature(
            $sdkKey,
            $sdkSecret,
            $schedule->zoom_meeting_id,
            $role
        );

        return view('zoom.join', [
            'signature' => $signature,
            'sdkKey' => $sdkKey,
            'meetingNumber' => $schedule->zoom_meeting_id,
            'password' => $schedule->passcode,
            'userName' => auth()->user()->name ?? 'Guest',
            'userEmail' => auth()->user()->email ?? 'email@example.com',
        ]);
    }
}
