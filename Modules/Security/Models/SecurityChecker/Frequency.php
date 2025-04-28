<?php
declare(strict_types=1);

namespace Modules\Security\Models\SecurityChecker;

use Modules\Security\Contracts\SecurityCheckerInterface;
use Modules\Security\Exceptions\SecurityViolationException;
use Modules\Security\Models\AttemptRequestEvent;
use Modules\Security\Models\ConfigProvider;
use Carbon\Carbon;

/**
 * Class Frequency
 *
 * @package Modules\Security\Models\SecurityChecker
 */
class Frequency implements SecurityCheckerInterface
{
    /**
     * @var \Modules\Security\Models\ConfigProvider
     */
    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function check($securityEventType, $accountReference = null)
    {
        $limitTimeBetweenRequests = $this->configProvider->getMinTimeBetweenPasswordResetRequests();
        if ($limitTimeBetweenRequests) {
            $lastRecordCreationTimestamp = $this->loadLastRecordCreationTimestamp(
                $securityEventType,
                $accountReference
            );
            if ($lastRecordCreationTimestamp && (
                    $limitTimeBetweenRequests >
                    (Carbon::now('UTC')->timestamp - $lastRecordCreationTimestamp)
                )) {
                throw new SecurityViolationException(
                    __('We received too many requests for on this event. Please wait and try again .')
                );
            }
        }
    }

    /**
     * Load last record creation timestamp
     *
     * @param int         $securityEventType
     * @param string|null $accountReference
     *
     * @return int
     */
    private function loadLastRecordCreationTimestamp(int $securityEventType, string $accountReference = null)
    {
        $record = AttemptRequestEvent::getLastRecord($securityEventType, $accountReference);

        return (int) $record?->created_at->timestamp;
    }
}
