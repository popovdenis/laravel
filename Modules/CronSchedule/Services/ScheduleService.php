<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Modules\CronSchedule\Models\CronSchedule;

/**
 * Class ScheduleService
 *
 * @package Modules\CronSchedule\Services
 */
class ScheduleService
{
    public function registerFor(string $targetType, string $artisanCommand, Schedule $schedule): void
    {
        $schedules = $this->getSchedulesFor($targetType);

        foreach ($schedules as $cron) {
            if (! $cron->enabled) {
                return;
            }

            $expression = $this->toCronExpression($cron);

            if (! $expression) {
                Log::warning("Invalid frequency '{$cron->frequency}' in CronSchedule #{$cron->id} for type {$targetType}");
                continue;
            }

            $schedule->command($artisanCommand)->cron($expression);
        }
    }

    protected function getSchedulesFor(string $targetType): \Illuminate\Support\Collection
    {
        return CronSchedule::query()
            ->where('enabled', true)
            ->where('target_type', $targetType)
            ->get();
    }

    protected function toCronExpression(CronSchedule $cron): ?string
    {
        return match ($cron->frequency) {
            'every_minute' => '* * * * *',
            'hourly'       => "{$cron->minutes} * * * *",
            'daily'        => "{$cron->minutes} {$cron->hours} * * *",
            'weekly'       => "{$cron->minutes} {$cron->hours} * * {$cron->day_of_week}",
            'monthly'      => "{$cron->minutes} {$cron->hours} {$cron->day} * *",
            default        => null,
        };
    }
}
