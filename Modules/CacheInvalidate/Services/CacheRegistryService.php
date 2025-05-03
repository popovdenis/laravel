<?php
declare(strict_types=1);

namespace Modules\CacheInvalidate\Services;

use Illuminate\Support\Collection;
use Modules\CacheInvalidate\Models\CacheItem;

/**
 * Class CacheRegistryService
 *
 * @package Modules\CacheInvalidate\Services
 */
class CacheRegistryService
{
    public function getCombined(): Collection
    {
        $dbItems = CacheItem::all()->keyBy('command');
        $configItems = collect(config('cacheinvalidate.cache-types.types', []))->keyBy('command');

        return $configItems->map(function ($item, $command) use ($dbItems) {
            if ($dbItems->has($command)) {
                return $dbItems->get($command)->setAttribute('from_config', true);
            }

            return new CacheItem([
                'cache_type' => $item['cache_type'],
                'command' => $command,
                'from_config' => true,
            ]);
        })->merge(
            $dbItems->reject(fn($item) => $configItems->has($item->command))
        );
    }
}
