<?php
/**
 * @package Modules\Booking\Enums
 */

namespace Modules\Booking\Enums;

enum BookingTypeEnum: string
{
    case BOOKING_TYPE_GROUPED = 'grouped';
    case BOOKING_TYPE_INDIVIDUAL = 'individual';
}
