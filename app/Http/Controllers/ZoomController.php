<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ZoomService;

class ZoomController extends Controller
{
    public function join(Schedule $schedule)
    {
        $user = auth()->user();

        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');

        if ($user->hasRole('Teacher')) {
            $signature = ZoomService::generateSignature(
                $sdkKey,
                $sdkSecret,
                $schedule->zoom_meeting_id,
                1
            );
        } else if ($user->hasRole('Student')) {
            $signature = ZoomService::generateSignature(
                $sdkKey,
                $sdkSecret,
                $schedule->zoom_meeting_id,
                0
            );
        }

        return view('zoom.join', [
            'signature' => $signature,
            'sdkKey' => $sdkKey,
            'meetingNumber' => $schedule->id,
            'password' => $schedule->passcode,
            'userName' => auth()->user()->name ?? 'Guest',
            'userEmail' => auth()->user()->email ?? 'email@example.com',
        ]);
    }
}
