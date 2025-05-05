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
    public function getTransactionPriceId();
    public function setTransactionPriceId($priceId);
}
