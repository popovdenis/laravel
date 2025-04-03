<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Config;

class ZoomService
{
    public function isPro(): bool
    {
        return Config::get('services.zoom.mode') === 'pro';
    }

    public function generateJoinUrl($schedule): string
    {
        return $this->isPro()
            ? $schedule->zoom_join_url
            : $schedule->custom_link;
    }

    public function generateStartUrl($schedule): string
    {
        return $this->isPro()
            ? $schedule->zoom_start_url
            : $schedule->custom_link;
    }
}
