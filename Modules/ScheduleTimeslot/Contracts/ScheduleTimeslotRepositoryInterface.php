<?php

namespace Modules\ScheduleTimeslot\Contracts;

/**
 * Interface ScheduleTimeslotRepositoryInterface
 *
 * @package Modules\ScheduleTimeslot\Contracts
 */
interface ScheduleTimeslotRepositoryInterface
{
    /**
     * Get info about product by product SKU
     *
     * @param int $id
     * @param bool $forceReload
     * @return \Modules\ScheduleTimeslot\Models\ScheduleTimeslot
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id, $forceReload = false);
}
