<?php

namespace Modules\Security\Contracts;

use Modules\Security\Models\AttemptRequestEvent;

/**
 * Interface AttemptRequestFactoryInterface
 *
 * @package Modules\Security\Contracts
 */
interface AttemptRequestFactoryInterface
{
    public function create(): AttemptRequestEventInterface;
}
