<?php
namespace Modules\Booking\Models\Enums;

/**
 * @package App\Models\Enums
 */
enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
