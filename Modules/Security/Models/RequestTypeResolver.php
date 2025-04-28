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
    public function resolve(RequestType $type): RequestTypeInterface
    {
        if (!$this->has($type)) {
            throw new \InvalidArgumentException("Unsupported request type: {$type->name}");
        }

        return match ($type) {
            RequestType::BOOKING_ATTEMPT_REQUEST => app(Booking::class),
        };
    }

    public function has(RequestType $type): bool
    {
        return in_array($type, [
            RequestType::BOOKING_ATTEMPT_REQUEST,
            RequestType::CUSTOMER_PASSWORD_RESET_REQUEST,
            RequestType::ADMIN_PASSWORD_RESET_REQUEST,
        ], true);
    }
}
