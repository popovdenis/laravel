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
    public const CONFIG_PATH_MAX_NUMBER_BOOKING_REQUESTS = 'max_number_booking_requests';
    public const CONFIG_PATH_MIN_TIME_BETWEEN_BOOKING_REQUESTS = 'min_time_between_booking_requests';

    protected $pathPrefix = 'security.';

    public function getMaxNumberBookingRequests(): int
    {
        return (int) $this->getValue(self::CONFIG_PATH_MAX_NUMBER_BOOKING_REQUESTS);
    }

    public function getMinTimeBetweenBookingRequests(): int
    {
        $timeInMin = (int) $this->getValue(self::CONFIG_PATH_MIN_TIME_BETWEEN_BOOKING_REQUESTS);

        return $timeInMin * 60;
    }
}
