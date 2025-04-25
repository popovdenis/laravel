<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;

/**
 * Class SubscriptionService
 *
 * @package App\Services
 */
class SubscriptionService
{
    /**
     * @var \App\Services\CreditBalanceService
     */
    private CreditBalanceService $creditBalanceService;

    public function __construct(\App\Services\CreditBalanceService $creditBalanceService)
    {
        $this->creditBalanceService = $creditBalanceService;
    }

    public function syncSubscriptionForUser(User $user, ?int $planId): void
    {
        $currentPlanId = $user->subscription?->plan_id;

        if ($planId === $currentPlanId) {
            // No changes, do nothing.
            return;
        }

        if ($planId) {
            $plan = $this->getUserSubscriptionPlan($planId);

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

            $this->updateUserSubscription($user, $planId, $startsAt, $endsAt, $trialEndsAt);
            $this->updateCreditBalance($user, $plan);
        } else {
            $user->subscription()?->delete();
        }
    }

    protected function getUserSubscriptionPlan(int $planId): SubscriptionPlan
    {
        return \App\Models\SubscriptionPlan::find($planId);
    }

    protected function updateUserSubscription(User $user, int $planId, $startsAt, $endsAt, $trialEndsAt): void
    {
        $user->subscription()->updateOrCreate([], [
            'plan_id'       => $planId,
            'starts_at'     => $startsAt,
            'ends_at'       => $endsAt,
            'trial_ends_at' => $trialEndsAt,
        ]);
    }

    protected function updateCreditBalance(User $user, SubscriptionPlan $plan): void
    {
        if ($plan->credits !== $user->credit_balance) {
            $this->creditBalanceService->updateUserCreditBalance($user, $plan->credits);
        }
    }
}
