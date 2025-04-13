<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Schedule;
use App\Notifications\MeetingCreatedNotification;

/**
 * Class ScheduleNotifier
 *
 * @package App\Services
 */
class ScheduleNotifier
{
    public static function notifyParticipants(Schedule $schedule): void
    {
        if (! $schedule->zoom_join_url) {
            return;
        }

        // teacher
        $notification = new MeetingCreatedNotification(
            url: route('schedule.join', ['schedule' => $schedule, 'role' => 1]),
            startsAt: $schedule->start_time,
        );
        $schedule->teacher?->notify($notification);

        // students
        $notification = new MeetingCreatedNotification(
            url: route('schedule.join', ['schedule' => $schedule, 'role' => 0]),
            startsAt: $schedule->start_time,
        );
        foreach ($schedule->students as $student) {
            $student->notify($notification);
        }
    }
}
