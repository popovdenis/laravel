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

        self::notifyTeacher($schedule);
        self::notifyStudent($schedule);
    }

    private static function notifyTeacher(Schedule $schedule): void
    {
        if ($schedule->notify_user && !$schedule->user_notified) {
            $notification = new MeetingCreatedNotification(
                url: route('schedule.join', ['schedule' => $schedule, 'role' => 1]),
                startsAt: $schedule->start_time,
            );
            $schedule->teacher?->notify($notification);
            $schedule->update([
                'user_notified' => true,
                'notify_user' => false,
            ]);
        }
    }

    private static function notifyStudent(Schedule $schedule): void
    {
        $notification = new MeetingCreatedNotification(
            url: route('schedule.join', ['schedule' => $schedule, 'role' => 0]),
            startsAt: $schedule->start_time,
        );
        foreach ($schedule->students as $student) {
            if ($student->pivot->notify_user && !$student->pivot->user_notified) {
                $student->notify($notification);
                $schedule->students()->updateExistingPivot($student->id, [
                    'user_notified' => true,
                    'notify_user' => false,
                ]);
            }
        }
    }
}
