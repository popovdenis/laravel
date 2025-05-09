<?php
declare(strict_types=1);

namespace App\Services;

use App\Mail\WelcomeEmail;
use App\Mail\MeetingNotification;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;

/**
 * Class EmailNotificationService
 *
 * @package App\Services
 */
class EmailNotificationService
{
    public function sendWelcomeEmail($user)
    {
        Mail::to($user->email)->send(
            new WelcomeEmail(
                name: $user->getFullNameAttribute(),
                email: $user->email,
                password: $user->password_plaint,
            )
        );
    }

    public function sendMeetingNotification(Schedule $schedule, $user, $userRole = 0): void
    {
        $joinUrl = route('schedule.join', ['schedule' => $schedule, 'role' => $userRole]);

        Mail::to($user->email)->send(
            new MeetingNotification(
                name: $user->getFullNameAttribute(),
                meetingTime: $schedule->start_time->toDayDateTimeString(),
                joinUrl: $joinUrl
            )
        );
    }
}
