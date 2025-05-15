<?php
declare(strict_types=1);

namespace Modules\ScheduleTimeslot\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\ScheduleTimeslot\Contracts\ScheduleTimeslotRepositoryInterface;

/**
 * Class ScheduleTimeslotRepository
 *
 * @package Modules\ScheduleTimeslot\Models
 */
class ScheduleTimeslotRepository implements ScheduleTimeslotRepositoryInterface
{
    public function getById($id, $forceReload = false)
    {
        // TODO: Implement caching and $forceReload
        $slot = ScheduleTimeslot::where('id', $id)->first();
        if (!$slot->id) {
            throw new ModelNotFoundException('Selected time slot is not available.');
        }

        return $slot;
    }
}
