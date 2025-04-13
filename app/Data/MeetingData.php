<?php
declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Carbon;

readonly class MeetingData
{
    public function __construct(
        public string $teacherEmail,
        public Carbon $startTime,
        public int $duration,
        public string $topic = 'Scheduled Lesson',
    ) {}
}
