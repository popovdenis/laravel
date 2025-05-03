<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class CacheManager extends Component
{
    public array $cacheTypes = [
        [
            'key' => 'config_cache',
            'label' => 'Config Cache',
            'description' => 'Cache the configuration files.',
            'command' => 'config:cache',
        ],
        [
            'key' => 'route_cache',
            'label' => 'Route Cache',
            'description' => 'Cache the application routes.',
            'command' => 'route:cache',
        ],
        [
            'key' => 'view_cache',
            'label' => 'View Cache',
            'description' => 'Compile Blade views.',
            'command' => 'view:cache',
        ],
        [
            'key' => 'clear_all',
            'label' => 'Clear All Cache',
            'description' => 'Clear all compiled files and configurations.',
            'command' => [
                'config:clear',
                'route:clear',
                'view:clear',
            ],
        ],
    ];

    public array $selected = [];
    public string $bulkAction = '';

    public function submitSelected(): void
    {
        foreach ($this->selected as $key) {
            $item = collect($this->cacheTypes)->firstWhere('key', $key);
            if (! $item || ! isset($item['command'])) {
                continue;
            }

            $commands = is_array($item['command']) ? $item['command'] : [$item['command']];
            foreach ($commands as $command) {
                Artisan::call($command);
            }
        }

        $this->selected = [];
        $this->bulkAction = '';

        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Selected caches have been refreshed.')
            ->success()
            ->send();
    }

    public function flushAll(): void
    {
        Artisan::call('optimize:clear');

        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Selected caches have been refreshed.')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.cache-manager');
    }

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public bool $selectAll = false;

    public function updatedSelectAll($value)
    {
        $this->selected = $value
            ? collect($this->cacheTypes)->pluck('key')->toArray()
            : [];
    }
}
