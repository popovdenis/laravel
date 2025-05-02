<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Order\Models\Quote;
use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Class SubscriptionQuote
 *
 * @package Modules\Subscription\Models
 */
class SubscriptionQuote extends Quote implements SubscriptionQuoteInterface
{
    protected User $user;
    protected int $plan_id;
    protected int $credits;

    public function getPaymentMethodConfig(): string
    {
        return setting(Subscription::PAYMENT_METHOD_CONFIG_PATH);
    }

    public function validate(): void
    {
        // TODO: Implement validate() method.
    }

    public function save(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->getModel();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAmount(): int
    {
        return $this->credits;
    }

    public function setAmount(int $amount): void
    {
        $this->credits = $amount;
    }

    public function getDescription(): string
    {
        return "Subscription to plan '{$this->plan_id}'";
    }

    public function getSourceType(): string
    {
        return SubscriptionPlan::class;
    }

    public function getSourceId(): int
    {
        return $this->plan_id;
    }

    public function setSourceId(int $sourceId)
    {
        $this->plan_id = $sourceId;
    }
}
