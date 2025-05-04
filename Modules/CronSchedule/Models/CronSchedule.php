<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 *
 * @package Modules\Schedule\Models
 */
class CronSchedule extends Model
{
    protected $fillable = [
        'enabled',
        'target_type',
        'command',
        'frequency',
        'day',
        'day_of_week',
        'hours',
        'minutes',
        'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function getTargetTypeNameAttribute(): string
    {
        return class_basename($this->target_type);
    }
}

