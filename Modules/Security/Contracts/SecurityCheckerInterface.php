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
     * @param int $securityEventType
     * @param string|null $accountReference
     *
     * @return void
     * @throws \Modules\Security\Exceptions\SecurityViolationException
     */
    public function check($securityEventType, $accountReference = null);
}
