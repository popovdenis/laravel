<?php
declare(strict_types=1);

namespace Modules\Subscription\Services;

use Modules\Payment\Contracts\TransactionServiceInterface;
use Modules\Subscription\Models\ConfigProvider;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Class SubscriptionService
 *
 * @package App\Services
 */
class SubscriptionService
{
    /**
     * @var \Modules\Payment\Contracts\TransactionServiceInterface
     */
    private TransactionServiceInterface $transactionService;
    private ConfigProvider $configProvider;

    public function __construct(
        TransactionServiceInterface $transactionService,
        ConfigProvider $configProvider
    )
    {
        $this->transactionService = $transactionService;
        $this->configProvider = $configProvider;
    }

    public function syncSubscriptionForUser(User $user, ?int $planId): void
    {
        if (!$user->hasRole('Student')) {
            throw new \Exception('Only student can subscribe to this plan.');
        }
        $currentPlanId = $user->userSubscription?->plan_id;

        if ($planId === $currentPlanId) {
            // No changes, do nothing.
            return;
        }

        if ($planId) {
            $plan = $this->getSubscriptionPlan($planId);

            $this->updateCreditBalance($user, $plan);
        } else {
            $user->subscriptions()?->delete();
        }
    }

    public function getSubscriptionPlan(int $planId): SubscriptionPlan
    {
        return \Modules\SubscriptionPlan\Models\SubscriptionPlan::find($planId);
    }

    public function updateCreditBalance(User $user, SubscriptionPlan $plan): void
    {
        if ($this->configProvider->resetCreditsOnPlanChange()) {
            $this->transactionService->adjustCredits($user, $user->credit_balance);
        }
        $this->transactionService->topUpCredits($user, $plan->credits);
    }

    public function getUpdateUserSubscriptionOptions($plan): array
    {
        $now = now();
        $trialEndsAt = null;

        if ($plan?->enable_trial && $plan->trial_days) {
            $trialEndsAt = $now->copy()->addDays($plan->trial_days);
        }

        $startsAt = $trialEndsAt ?? $now;

        $endsAt = match ($plan->frequency_unit) {
            'day'    => $startsAt->copy()->addDays($plan->frequency),
            'week'   => $startsAt->copy()->addWeeks($plan->frequency),
            'month'  => $startsAt->copy()->addMonths($plan->frequency),
            'year'   => $startsAt->copy()->addYears($plan->frequency),
            default  => null,
        };

        return [
            'plan_id'        => $plan->id,
            'starts_at'      => $startsAt,
            'ends_at'        => $endsAt,
            'trial_ends_at'  => $trialEndsAt,
            'credits_amount' => $plan->credits
        ];
    }
}
