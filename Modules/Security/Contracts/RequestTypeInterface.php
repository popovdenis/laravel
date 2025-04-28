<?php

namespace Modules\Security\Contracts;

/**
 * Interface RequestTypeInterface
 *
 * @package Modules\Security\Contracts
 */
interface RequestTypeInterface
{
    public function getMaxNumberRequests(): int;

    public function getMinTimeBetweenRequests(): int;
}
