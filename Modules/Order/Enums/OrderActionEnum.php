<?php
/**
 * @package Modules\Order\Enums
 */

namespace Modules\Order\Enums;

enum OrderActionEnum: string
{
    case ORDER_ACTION_PLACED = 'placed';
    case ORDER_ACTION_CANCELLED = 'cancelled';
}
