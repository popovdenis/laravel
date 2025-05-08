<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\Booking\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    public const CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG = 'reindex.required';

    protected $pathPrefix = 'booking.';

    public function markBookingReindex($value): void
    {
        $this->setValue(self::CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG, $value);
    }

    public function getBookingReindexRequiredFlag(): bool
    {
        return (bool) $this->getValue(self::CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG);
    }
}
