<?php

namespace Modules\CronSchedule\Contracts;

use Modules\CronSchedule\Models\CronScheduledTaskLogItem;
use Throwable;

/**
 * Interface ScheduledTaskLoggerInterface
 *
 * @package Modules\CronSchedule\Contracts
 */
interface ScheduledTaskLoggerInterface
{
    public function start(string $name): CronScheduledTaskLogItem;

    public function success(CronScheduledTaskLogItem $log): void;

    public function failure(CronScheduledTaskLogItem $log, Throwable $e): void;

    public function skipped(CronScheduledTaskLogItem $log): void;
}
