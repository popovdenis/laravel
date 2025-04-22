<?php

namespace App\Models\Enums;

enum StreamStatus: string
{
    case PLANNED = 'planned';
    case STARTED = 'started';
    case PAUSED = 'paused';
    case FINISHED = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => 'Planned',
            self::STARTED => 'Started',
            self::PAUSED => 'Paused',
            self::FINISHED => 'Finished',
        };
    }
}
