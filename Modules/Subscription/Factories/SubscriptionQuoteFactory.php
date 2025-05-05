<?php
declare(strict_types=1);

namespace Modules\Subscription\Factories;

use Modules\Payment\Contracts\RequestDataInterface;
use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionQuote;

/**
 * Class SubscriptionQuoteFactory
 *
 * @package Modules\Subscription\Factories
 */
class SubscriptionQuoteFactory
{
    public function create(RequestDataInterface $requestData): SubscriptionQuoteInterface
    {
        $quote = app()->make(SubscriptionQuote::class);
        $quote->setUser($requestData->student);
        $quote->setSourceId($requestData->planId);
        $quote->setAmount($requestData->amount);
        $quote->setTransactionPriceid($requestData->transactionPriceId);
        $quote->getPayment()->importData($requestData);
        $quote->setModel(app(Subscription::class));

        return $quote;
    }
}
