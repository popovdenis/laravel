<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Schedule;

/**
 * Class ScheduleNotifier
 *
 * @package App\Services
 */
class ScheduleNotifier
{
    public function __construct(protected EmailNotificationService $emailNotificationService)
    {
    }

    public function notifyParticipants(Schedule $schedule): void
    {
        if (! $schedule->zoom_join_url) {
            return;
        }
        if (!setting('mailsender.use_mail_sender')) {
            return;
        }

        $this->notifyTeacher($schedule);
        $this->notifyStudent($schedule);
    }

    private function notifyTeacher(Schedule $schedule): void
    {
        if ($schedule->notify_user && !$schedule->user_notified) {
            $this->emailNotificationService->sendMeetingNotification($schedule, $schedule->teacher, 1);

            $schedule->update([
                'user_notified' => true,
                'notify_user' => false,
            ]);
        }
    }

    private function notifyStudent(Schedule $schedule): void
    {
        foreach ($schedule->students as $student) {
            if ($student->pivot->notify_user && !$student->pivot->user_notified) {
                $this->emailNotificationService->sendMeetingNotification($schedule, $student);

                $schedule->students()->updateExistingPivot($student->id, [
                    'user_notified' => true,
                    'notify_user' => false,
                ]);
            }
        }
    }
}
