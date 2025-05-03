<?php
declare(strict_types=1);

namespace Modules\CacheInvalidate\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CacheItem
 *
 * @package Modules\CacheInvalidate\Models
 */
class CacheItem extends Model
{
    protected $fillable = [
        'cache_type',
        'command',
    ];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean',
    ];
}

