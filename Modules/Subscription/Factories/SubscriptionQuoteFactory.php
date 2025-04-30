<?php
declare(strict_types=1);

namespace Modules\Subscription\Factories;

use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\Subscription\Models\SubscriptionQuote;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Class SubscriptionQuoteFactory
 *
 * @package Modules\Subscription\Factories
 */
class SubscriptionQuoteFactory
{
    public function create(User $user, int $planId, int $credits): SubscriptionQuoteInterface
    {
        $quote = new SubscriptionQuote();
        $quote->setUser($user);
        $quote->setPlanId($planId);
        $quote->setAmount($credits);

        return $quote;
    }
}
