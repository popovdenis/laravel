<?php
/**
 * @package Modules\UserCreditHistory\Enums
 */

namespace Modules\UserCreditHistory\Enums;

enum CreditHistorySourceEnum: string
{
    case SUBSCRIPTION = 'subscription';
    case MANUAL = 'manual';
    case PROMO = 'promo';
    case ADJUSTMENT = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::SUBSCRIPTION => 'Subscription',
            self::MANUAL => 'Manual',
            self::PROMO => 'Promo',
            self::ADJUSTMENT => 'Adjustment',
        };
    }
}
