<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Modules\CronSchedule\Models\CronSchedule;
use Modules\CronSchedule\Contracts\ScheduledTaskLoggerInterface;
use Throwable;

/**
 * Class ScheduleService
 *
 * @package Modules\CronSchedule\Services
 */
class ScheduleService
{
    private ScheduledTaskLoggerInterface $scheduledTaskLogger;

    public function __construct(ScheduledTaskLoggerInterface $scheduledTaskLogger)
    {
        $this->scheduledTaskLogger = $scheduledTaskLogger;
    }

    public function registerFor(string $targetType, string $artisanCommand, Schedule $schedule): void
    {
        $schedules = $this->getSchedulesFor($targetType);

        foreach ($schedules as $cron) {
            if (! $cron->enabled) {
                continue;
            }

            $expression = $this->toCronExpression($cron);

            if (! $expression) {
                Log::warning("Invalid frequency '{$cron->frequency}' in CronSchedule #{$cron->id} for type {$targetType}");
                continue;
            }

            $logger = $this->scheduledTaskLogger;

            $schedule->command($artisanCommand)
                ->before(function () use ($logger, $artisanCommand) {
                    $log = $logger->start($artisanCommand);
                    cache()->put('cron-log-' . $artisanCommand, $log->id, now()->addMinutes(10));
                })
                ->after(function () use ($logger, $artisanCommand) {
                    if ($id = cache()->pull('cron-log-' . $artisanCommand)) {
                        $log = \Modules\CronSchedule\Models\CronScheduledTaskLogItem::find($id);
                        if ($log) {
                            $logger->success($log);
                        }
                    }
                })
                ->onFailure(function (Throwable $e) use ($logger, $artisanCommand) {
                    if ($id = cache()->pull('cron-log-' . $artisanCommand)) {
                        $log = \Modules\CronSchedule\Models\CronScheduledTaskLogItem::find($id);
                        if ($log) {
                            $logger->failure($log, $e);
                        }
                    }
                })
                ->cron($expression);
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
