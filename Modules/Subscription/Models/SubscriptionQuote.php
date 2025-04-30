<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\Subscription\Services\SubscriptionService;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Class SubscriptionQuote
 *
 * @package Modules\Subscription\Models
 */
class SubscriptionQuote implements SubscriptionQuoteInterface
{
    protected User $user;
    protected int $plan_id;
    protected int $credits;

    public function validate(): void
    {
//        if (!$this->plan->isActive()) {
//            throw new SubscriptionValidationException('Selected plan is not available.');
//        }
//
//        if ($this->user->hasActiveSubscription()) {
//            throw new SubscriptionValidationException('User already has an active subscription.');
//        }
    }

    public function getPaymentMethodConfig(): string
    {
        return setting(Subscription::PAYMENT_METHOD_CONFIG_PATH);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getPlanId(): int
    {
        return $this->plan_id;
    }

    public function setPlanId(int $planId): void
    {
        $this->plan_id = $planId;
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

    public function save(): ?\Illuminate\Database\Eloquent\Model
    {
        $user = $this->getUser();

        $paymentMethod = 'pm_card_visa'; // тестовый метод Stripe (подставной)
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);

        // TODO: implement swap
//        if ($user->subscribed('default')) {
//            $user->subscription('default')->swap('price_id_for_pro'); // keep the current plan till it ends
//            $user->subscription('default')->swapAndInvoice('price_id_for_pro'); // switch now
//        } else {
            $subscription = $user->newSubscription('default', 'price_1RJIH504fVTImIORseJmgDpt')->create($paymentMethod);
//        }

        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = app(SubscriptionService::class);
        $plan = $subscriptionService->getSubscriptionPlan($this->getPlanId());

        $subscription->update($subscriptionService->getUpdateUserSubscriptionOptions($plan));
        $subscriptionService->updateCreditBalance($user, $plan);

        return $subscription;
    }
}
