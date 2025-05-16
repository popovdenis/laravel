<?php
/**
 * @package Modules\Booking\Enums
 */

namespace Modules\Booking\Enums;

enum BookingTypeEnum: string
{
    case BOOKING_TYPE_GROUP = 'group';
    case BOOKING_TYPE_INDIVIDUAL = 'individual';

    public function type(): string
    {
        return match ($this) {
            self::BOOKING_TYPE_GROUP      => 'group',
            self::BOOKING_TYPE_INDIVIDUAL   => 'individual'
        };
    }
}
