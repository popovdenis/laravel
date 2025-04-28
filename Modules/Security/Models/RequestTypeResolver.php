<?php
declare(strict_types=1);

namespace Modules\Security\Models;

use Modules\Security\Contracts\RequestTypeInterface;
use Modules\Security\Models\Enums\RequestType;
use Modules\Security\Models\SecurityChecker\RequestType\Booking;

/**
 * Class RequestTypeResolver
 *
 * @package Modules\Security\Models
 */
class RequestTypeResolver
{
    public function resolve(RequestType $method): RequestTypeInterface
    {
        return match ($method) {
            RequestType::BOOKING_ATTEMPT_REQUEST => app(Booking::class),
            default              => throw new \InvalidArgumentException('Unsupported request type.'),
        };
    }
}
