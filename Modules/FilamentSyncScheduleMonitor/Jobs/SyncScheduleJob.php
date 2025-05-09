<?php
declare(strict_types=1);

namespace Modules\FilamentSyncScheduleMonitor\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

/**
 * Class SyncScheduleJob
 *
 * @package Modules\FilamentSyncScheduleMonitor\Jobs
 */
class SyncScheduleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle()
    {
        Artisan::call('schedule-monitor:sync {--keep-old}');
    }
}
