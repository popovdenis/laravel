<?php

namespace Modules\Subscription\Contracts;

use Modules\Order\Contracts\QuoteInterface;

/**
 * Interface SubscriptionQuoteInterface
 *
 * @package Modules\Subscription\Contracts
 */
interface SubscriptionQuoteInterface extends QuoteInterface
{
    public function setPlanId(int $planId): void;
    public function getPlanId(): int;
}
