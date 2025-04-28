<?php
declare(strict_types=1);

namespace Modules\Security\Models\SecurityChecker\RequestType;

use Modules\Security\Contracts\RequestTypeInterface;
use Modules\Security\Models\ConfigProvider;

/**
 * Class Booking
 *
 * @package Modules\Security\Models\SecurityChecker\RequestType
 */
class Booking implements RequestTypeInterface
{
    /**
     * @var \Modules\Security\Models\ConfigProvider
     */
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function getMaxNumberRequests(): int
    {
        return $this->configProvider->getMaxNumberBookingRequests();
    }

    public function getMinTimeBetweenRequests(): int
    {
        return $this->configProvider->getMinTimeBetweenBookingRequests();
    }
}
