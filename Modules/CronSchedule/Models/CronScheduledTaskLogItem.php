<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CronScheduledTaskLogItem
 *
 * @package Modules\CronSchedule\Models
 */
class CronScheduledTaskLogItem extends Model
{
    protected $fillable = [
        'name',
        'started_at',
        'finished_at',
        'status',
        'exception_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
