<?php
declare(strict_types=1);

namespace Modules\Security\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\Security\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    public const CONFIG_PATH_MIN_TIME_BETWEEN_BOOKING_REQUESTS = 'min_time_between_booking_requests';

    protected $pathPrefix = 'security.';

    public function getMinTimeBetweenPasswordResetRequests(): int
    {
        $timeInMin = (int) $this->getValue(self::CONFIG_PATH_MIN_TIME_BETWEEN_BOOKING_REQUESTS);

        return $timeInMin * 60;
    }
}
