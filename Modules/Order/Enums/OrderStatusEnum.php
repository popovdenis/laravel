<?php
/**
 * @package Modules\Order\Enums
 */

namespace Modules\Order\Enums;

enum OrderStatusEnum: string
{
    case ORDER_STATUS_PENDING = 'pending';
    case ORDER_STATUS_PROCESSING = 'processing';
    case ORDER_STATUS_COMPLETE = 'complete';
    case ORDER_STATUS_CANCELLED = 'cancelled';
}
