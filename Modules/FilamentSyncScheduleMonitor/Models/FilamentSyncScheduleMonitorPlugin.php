<?php
declare(strict_types=1);

namespace Modules\FilamentSyncScheduleMonitor\Models;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Modules\FilamentSyncScheduleMonitor\Http\Livewire\SyncScheduleMonitor;

/**
 * Class FilamentSyncScheduleMonitorPlugin
 *
 * @package Modules\FilamentSyncScheduleMonitor\Models
 */
class FilamentSyncScheduleMonitorPlugin implements Plugin
{
    const PACKAGE = 'filament-sync-schedule-monitor';

    const ID = 'filament-sync-schedule-monitor';

    const VERSION = '1.0.0';

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return static::ID;
    }

    public function register(Panel $panel): void
    {
        Livewire::component(
            'filament-sync-schedule-monitor::sync-schedule-button',
            config('filament-sync-schedule-monitor.livewireComponentClass', SyncScheduleMonitor::class)
        );

        $panel->renderHook(
            name: 'panels::user-menu.before',
            hook: fn (): string => Blade::render('@livewire(\'filament-sync-schedule-monitor::sync-schedule-button\')'),
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
