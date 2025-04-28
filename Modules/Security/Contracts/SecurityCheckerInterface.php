<?php

namespace Modules\Security\Contracts;

/**
 * Interface SecurityCheckerInterface
 *
 * @package Modules\Security\Contracts
 */
interface SecurityCheckerInterface
{
    /**
     * Perform security checks
     *
     * @param \Modules\Security\Contracts\RequestTypeInterface $requestType
     * @param string                                           $eventKey
     *
     * @return void
     * @throws \Modules\Security\Exceptions\SecurityViolationException
     */
    public function checkAndHit(RequestTypeInterface $requestType, string $eventKey): void;
}
