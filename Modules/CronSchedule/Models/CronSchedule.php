<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Schedule
 *
 * @package Modules\Schedule\Models
 */
class CronSchedule extends Model
{
    protected $fillable = [
        'cron_schedulable_id',
        'cron_schedulable_type',
        'cron_expression',
        'description',
    ];

    public function cronSchedulable(): MorphTo
    {
        return $this->morphTo();
    }
}

