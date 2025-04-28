<?php
declare(strict_types=1);

namespace Modules\Security\Models\SecurityChecker;

use Illuminate\Support\Facades\RateLimiter;
use Modules\Security\Contracts\RequestTypeInterface;
use Modules\Security\Contracts\SecurityCheckerInterface;
use Modules\Security\Exceptions\SecurityViolationException;

/**
 * Class Frequency
 *
 * @package Modules\Security\Models\SecurityChecker
 */
class Frequency implements SecurityCheckerInterface
{
    public function checkAndHit(RequestTypeInterface $requestType, string $eventKey): void
    {
        $maxNumberRequests = $requestType->getMaxNumberRequests();
        if ($maxNumberRequests && RateLimiter::tooManyAttempts($eventKey, $maxNumberRequests)) {
            throw new SecurityViolationException(__('Booking limit reached. Try again in a few minutes.'));
        }

        $limitTimeBetweenRequests = $requestType->getMinTimeBetweenRequests();
        if ($limitTimeBetweenRequests) {
            RateLimiter::hit($eventKey, $limitTimeBetweenRequests);
        }
    }
}
