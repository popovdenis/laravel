<?php
/**
 * @package Modules\SubscriptionPlan\Enums
 */

namespace Modules\SubscriptionPlan\Enums;

enum DiscountTypeEnum: string
{
    case FIXED = 'fixed';
    case PERCENT = 'percent';

    public function label(): string
    {
        return match ($this) {
            self::FIXED => 'Fixed',
            self::PERCENT => 'Percent',
        };
    }
}
