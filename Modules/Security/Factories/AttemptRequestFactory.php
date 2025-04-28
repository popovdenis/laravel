<?php
declare(strict_types=1);

namespace Modules\Security\Factories;

use Modules\Security\Contracts\AttemptRequestEventInterface;
use Modules\Security\Contracts\AttemptRequestFactoryInterface;
use Modules\Security\Models\AttemptRequestEvent;

/**
 * Class AttemptRequestFactory
 *
 * @package Modules\Security\Factories
 */
class AttemptRequestFactory implements AttemptRequestFactoryInterface
{
    public function create(): AttemptRequestEventInterface
    {
        return new AttemptRequestEvent();
    }
}
