<?php

namespace Modules\Subscription\Contracts;

use Modules\Order\Contracts\QuoteInterface;
use Modules\SubscriptionPlan\Contracts\SubscriptionPlanInterface;

/**
 * Interface SubscriptionQuoteInterface
 *
 * @package Modules\Subscription\Contracts
 */
interface SubscriptionQuoteInterface extends QuoteInterface
{
    public function getTransactionPriceId();
    public function setTransactionPriceId($priceId);

    public function setPlan(SubscriptionPlanInterface $plan);
    public function getPlan(): SubscriptionPlanInterface;
}
