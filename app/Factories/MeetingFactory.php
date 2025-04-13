<?php
declare(strict_types=1);

namespace App\Factories;

use App\Data\MeetingData;
use App\Models\Schedule;

class MeetingFactory
{
    public static function fromSchedule(Schedule $schedule, string $topic, int $duration): MeetingData
    {
        return new MeetingData(
            teacherEmail: $schedule->teacher->email,
            startTime: $schedule->start_time,
            duration: $duration,
            topic: $topic
        );
    }
}
