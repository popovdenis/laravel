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
        if ($plan->credits !== $user->credit_balance) {
            $this->transactionService->calculateBalanceWithSubscription($user, $plan);
        }
    }
}
