<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\Controller;

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

    public function join(Schedule $schedule, $role = null)
    {
        $user = auth()->user();

        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');
        $role = $role === null ? ($user->hasRole('Teacher') ? 1 : 0) : $role;

        $signature = ZoomService::generateSignature(
            $sdkKey,
            $sdkSecret,
            $schedule->zoom_meeting_id,
            $role
        );

        $schedule->syncScheduleChat($schedule);

        return view('zoom.join', [
            'signature' => $signature,
            'sdkKey' => $sdkKey,
            'meetingNumber' => $schedule->zoom_meeting_id,
            'password' => $schedule->passcode,
            'userName' => auth()->user()->name ?? 'Guest',
            'userEmail' => auth()->user()->email ?? 'email@example.com',
            'schedule' => $schedule,
        ]);
    }
}
