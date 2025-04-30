<?php
/**
 * @package Modules\UserCreditHistory\Enums
 */

namespace Modules\Subscription\Enums;

enum TransactionStatus: string
{
    case PURCHASE = 'purchase';
    case REFUND = 'refund';
}
