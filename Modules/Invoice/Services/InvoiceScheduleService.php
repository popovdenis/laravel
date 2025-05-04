<?php
declare(strict_types=1);

namespace Modules\Invoice\Services;

use Illuminate\Console\Scheduling\Schedule;
use Modules\CronSchedule\Services\ScheduleService;
use Modules\Invoice\Models\Invoice;

/**
 * Class InvoiceScheduleService
 *
 * @package Modules\Invoice\Services
 */
class InvoiceScheduleService
{
    private ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function register(Schedule $schedule): void
    {
        $this->scheduleService->registerFor(targetType: Invoice::class, schedule: $schedule);
    }
}
