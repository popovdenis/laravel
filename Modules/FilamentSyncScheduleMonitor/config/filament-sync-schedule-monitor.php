<?php

return [
    // Command to run when clearing the cache
    'default_commands' => [
        'cache:clear',
    ],

    // Session name for the indicator count
    'changes_count' => 'filament-sync-schedule-monitor',

    // Livewire component for clear cache button in header.
    'livewireComponentClass' => Modules\FilamentSyncScheduleMonitor\Http\Livewire\SyncScheduleMonitor::class,

    // Permissions check
    'permissions' => false,

    // Role check
    'role' => false,
];
