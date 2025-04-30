<?php
declare(strict_types=1);

namespace Modules\Subscription\Services;

use Modules\Payment\Contracts\TransactionServiceInterface;
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

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
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

            $this->updateUserSubscription($user, $this->getUpdateUserSubscriptionOptions($plan));
            $this->updateCreditBalance($user, $plan);
        } else {
            $user->subscriptions()?->delete();
        }
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

    public function getSubscriptionPlan(int $planId): SubscriptionPlan
    {
        return \Modules\SubscriptionPlan\Models\SubscriptionPlan::find($planId);
    }

    protected function updateUserSubscription(User $user, array $options): void
    {
        $user->userSubscriptions()->updateOrCreate([], $options);
    }

    public function updateCreditBalance(User $user, SubscriptionPlan $plan): void
    {
        if ($plan->credits !== $user->credit_balance) {
            $this->transactionService->calculateBalanceWithSubscription($user, $plan);
        }
    }
}
