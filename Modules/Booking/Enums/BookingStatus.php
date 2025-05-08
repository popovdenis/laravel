<?php
namespace Modules\Booking\Enums;

/**
 * @package App\Models\Enums
 */
enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'success',
            self::CONFIRMED => 'warning',
            self::CANCELLED => 'danger',
        };
    }
}
