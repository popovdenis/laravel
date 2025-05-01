<?php
/**
 * @package Modules\Order\Enums
 */

namespace Modules\Order\Enums;

enum OrderStateEnum: string
{
    case ORDER_STATE_NEW = 'new';
    case ORDER_STATE_PENDING = 'pending';
    case ORDER_STATE_PROCESSING = 'processing';
    case ORDER_STATE_COMPLETE = 'complete';
    case ORDER_STATE_CANCELLED = 'cancelled';
}
