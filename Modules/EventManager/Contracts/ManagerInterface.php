<?php

namespace Modules\EventManager\Contracts;

/**
 * Interface ManagerInterface
 *
 * @package Modules\EventManager\Contracts
 */
interface ManagerInterface
{
    /**
     * Dispatch event
     *
     * Calls all observer callbacks registered for this event
     * and multiple observers matching event name pattern
     *
     * @param string $eventName
     * @param array $data
     *
     * @return void
     */
    public function dispatch($eventName, array $data = []);
}
