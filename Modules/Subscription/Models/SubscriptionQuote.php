<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Order\Models\Quote;
use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\SubscriptionPlan\Contracts\SubscriptionPlanInterface;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;
use Modules\UserSubscription\Contracts\CustomerPaymentValidatorInterface;

/**
 * Class SubscriptionQuote
 *
 * @package Modules\Subscription\Models
 */
class SubscriptionQuote extends Quote implements SubscriptionQuoteInterface
{
    protected int $plan_id;
    protected int $credits;
    protected string $transaction_price_id;
    protected SubscriptionPlanInterface $plan;
    private CustomerPaymentValidatorInterface $customerPaymentValidator;

    public function __construct(CustomerPaymentValidatorInterface $customerPaymentValidator)
    {
        $this->customerPaymentValidator = $customerPaymentValidator;
    }

    public function getPaymentMethodConfig(): string
    {
        return setting(Subscription::PAYMENT_METHOD_CONFIG_PATH);
    }

    public function validate(): void
    {
        $this->customerPaymentValidator->validate($this);
    }

    public function save(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->getModel();
    }

    public function getAmount()
    {
        return $this->credits;
    }

    public function setAmount(int $amount)
    {
        $this->credits = $amount;
    }

    public function getDescription()
    {
        return "Subscription to plan '{$this->plan_id}'";
    }

    public function getSourceType()
    {
        return SubscriptionPlan::class;
    }

    public function getSourceId()
    {
        return $this->plan_id;
    }

    public function setSourceId(int $sourceId)
    {
        $this->plan_id = $sourceId;
    }

    public function getTransactionPriceId()
    {
        return $this->transaction_price_id;
    }

    public function setTransactionPriceId($priceId)
    {
        $this->transaction_price_id = $priceId;
    }

    public function setPlan(SubscriptionPlanInterface $plan)
    {
        $this->plan = $plan;
    }

    public function getPlan(): SubscriptionPlanInterface
    {
        return $this->plan;
    }
}
