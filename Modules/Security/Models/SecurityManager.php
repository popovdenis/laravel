<?php
declare(strict_types=1);

namespace Modules\Security\Models;

use Illuminate\Support\Facades\RateLimiter;
use Modules\Security\Contracts\RequestTypeInterface;
use Modules\Security\Contracts\SecurityCheckerInterface;
use Modules\Security\Models\Enums\RequestType;

/**
 * Class SecurityManager
 *
 * @package Modules\Security\Models
 */
class SecurityManager
{
    /**
     * @var \Modules\Security\Contracts\SecurityCheckerInterface
     */
    private SecurityCheckerInterface $securityChecker;
    /**
     * @var \Modules\Security\Models\RequestTypeResolver
     */
    private RequestTypeResolver $typeResolver;

    public function __construct(
        SecurityCheckerInterface $securityChecker,
        RequestTypeResolver $typeResolver,
    )
    {
        $this->securityChecker = $securityChecker;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function generateEventKey(array $params): string
    {
        return implode(':', $params);
    }

    /**
     * Perform security check
     *
     * @param \Modules\Security\Models\Enums\RequestType $requestType
     * @param string                                     $accountReference
     *
     * @return $this
     * @throws \Modules\Security\Exceptions\SecurityViolationException
     */
    public function performSecurityCheck(RequestType $requestType, string $accountReference): static
    {
        $requestTypeEntity = $this->typeResolver->resolve($requestType);
        $this->securityChecker->throttleAttempt(
            $requestTypeEntity,
            $accountReference
        );

        $this->createNewAttemptRequestEventRecord($requestTypeEntity, $accountReference);

        return $this;
    }

    /**
     * @param \Modules\Security\Contracts\RequestTypeInterface $requestType
     * @param                                                  $accountReference
     *
     */
    protected function createNewAttemptRequestEventRecord(RequestTypeInterface $requestType, $accountReference): void
    {
        $limitTimeBetweenRequests = $requestType->getMinTimeBetweenRequests();
        if ($limitTimeBetweenRequests) {
            RateLimiter::hit($accountReference, $limitTimeBetweenRequests);
        }
    }
}
