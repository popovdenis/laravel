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
    public function registerFor(string $targetType, Schedule $schedule): void
    {
        foreach ($this->getSchedulesFor($targetType) as $cron) {
            if (! $cron->enabled) {
                continue;
            }

            $allowedCommands = array_column(app('config')->get('cron-commands'), 'command');
            if (! in_array($cron->command, $allowedCommands, true)) {
                Log::warning("Unregistered command {$cron->command} for {$targetType}");
                continue;
            }

            $artisanCommand = $cron->command;

            $schedule->command($artisanCommand)->cron($this->toCronExpression($cron))->monitorName($artisanCommand);
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
