<?php
declare(strict_types=1);

namespace Modules\Schedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Schedule
 *
 * @package Modules\Schedule\Models
 */
class Schedule extends Model
{
    protected $fillable = [
        'schedulable_id',
        'schedulable_type',
        'cron_expression',
        'description',
    ];

    public function schedulable(): MorphTo
    {
        return $this->morphTo();
    }
}

