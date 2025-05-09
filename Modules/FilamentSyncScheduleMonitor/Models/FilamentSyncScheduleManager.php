<?php
declare(strict_types=1);

namespace Modules\FilamentSyncScheduleMonitor\Models;

use Closure;
use Illuminate\Support\Traits\Macroable;

/**
 * Class FilamentSyncScheduleManager
 *
 * @package Modules\FilamentSyncScheduleMonitor\Models
 */
class FilamentSyncScheduleManager
{
    use Macroable;

    protected array $commands = [];

    public function addCommand(string | Closure $command, array $params = []): static
    {
        $this->commands[] = $params ? [$command, $params] : $command;

        return $this;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
