<?php
/**
 * @package Modules\BookingCreditHistory\Models\Enums
 */

namespace Modules\BookingCreditHistory\Models\Enums;

enum BookingAction: string
{
    case SPEND      = 'spend';
    case REFUND     = 'refund';
    case ADJUSTMENT = 'adjustment';
}
