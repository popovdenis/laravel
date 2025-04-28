<?php
/**
 * @package Modules\BookingCreditHistory\Models\Enums
 */

namespace Modules\BookingCreditHistory\Enums;

enum BookingAction: string
{
    case SPEND      = 'spend';
    case REFUND     = 'refund';
    case ADJUSTMENT = 'adjustment';

    public function color(): string
    {
        return match ($this) {
            self::SPEND => 'success',
            self::ADJUSTMENT => 'warning',
            self::REFUND => 'danger',
        };
    }
}
