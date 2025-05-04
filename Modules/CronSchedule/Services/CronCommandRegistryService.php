<?php
declare(strict_types=1);

namespace Modules\CronSchedule\Services;

/**
 * Class CronCommandRegistryService
 *
 * @package Modules\CronSchedule\Services
 */
namespace Modules\CronSchedule\Services;

class CronCommandRegistryService
{
    public function all(): array
    {
        $all = [];

        foreach ($this->getCronCommands() as $command) {
            $all = array_merge($all, $command['commands']);
        }

        return $all;
    }

    public function optionsForSelect(): array
    {
        $result = [];

        foreach ($this->getCronCommands() as $command) {
            if (isset($command['command'], $command['label'])) {
                $result[$command['command']] = $command['label'];
            }
        }

        return $result;
    }

    private function getCronCommands()
    {
        return app('config')->get('cron-commands');
    }
}
