<?php
declare(strict_types=1);

namespace Modules\EventManager\Models\Event;

use Modules\EventManager\Contracts\ManagerInterface;
use Illuminate\Support\Facades\Event;

/**
 * Class Manager
 *
 * @package Modules\EventManager\Models\Event
 */
class Manager implements ManagerInterface
{
    public function dispatch($eventName, array $data = [])
    {
        $eventName = mb_strtolower($eventName);

        Event::dispatch($eventName, [$data]);
    }
}
