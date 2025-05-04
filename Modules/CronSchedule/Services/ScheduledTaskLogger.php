<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Services;

use Modules\CronSchedule\Contracts\ScheduledTaskLoggerInterface;
use Modules\CronSchedule\Models\CronScheduledTaskLogItem;
use Throwable;

/**
 * Class ScheduledTaskLogger
 *
 * @package Modules\CronSchedule\Services
 */
class ScheduledTaskLogger implements ScheduledTaskLoggerInterface
{
    public function start(string $name): CronScheduledTaskLogItem
    {
        return CronScheduledTaskLogItem::create([
            'name' => $name,
            'started_at' => now(),
            'status' => 'running',
        ]);
    }

    public function success(CronScheduledTaskLogItem $log): void
    {
        $log->update([
            'finished_at' => now(),
            'status' => 'success',
        ]);
    }

    public function failure(CronScheduledTaskLogItem $log, Throwable $e): void
    {
        $log->update([
            'finished_at' => now(),
            'status' => 'failed',
            'exception_message' => $e->getMessage(),
        ]);
    }

    public function skipped(CronScheduledTaskLogItem $log): void
    {
        $log->update([
            'finished_at' => now(),
            'status' => 'skipped',
        ]);
    }
}
