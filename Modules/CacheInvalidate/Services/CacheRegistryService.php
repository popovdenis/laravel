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
        $dbItems = CacheItem::all()->keyBy('cache_type');
        $configItems = collect(config('cacheinvalidate.cache-types.types', []));

        return $configItems->map(function ($item, $key) use ($dbItems) {
            if ($dbItems->has($key)) {
                return $dbItems->get($key)->setAttribute('from_config', true);
            }

            return new CacheItem([
                'cache_type' => $item['cache_type'],
                'command' => $item['command'],
                'description' => $item['description'] ?? '',
                'tag' => $item['tag'] ?? null,
                'status' => $item['status'] ?? true,
                'from_config' => true,
            ]);
        })->merge(
            $dbItems->reject(fn($item) => $configItems->has($item->cache_type))
        );
    }

    public function updateStatuses(array $types, bool $status): void
    {
        foreach ($types as $cacheType) {
            CacheItem::updateOrCreate(
                ['cache_type' => $cacheType],
                ['status' => $status]
            );
        }
    }
}
