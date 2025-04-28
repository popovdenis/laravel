<?php
declare(strict_types=1);

namespace Modules\Security\Models;

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
     * @param string                                     $eventKey
     *
     * @return $this
     * @throws \Modules\Security\Exceptions\SecurityViolationException
     */
    public function performSecurityCheck(RequestType $requestType, string $eventKey): static
    {
        $this->securityChecker->checkAndHit(
            $this->typeResolver->resolve($requestType),
            $eventKey
        );

        return $this;
    }
}
