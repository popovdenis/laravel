<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Payment\Contracts\TransactionServiceInterface;
use Modules\Subscription\Exceptions\InsufficientCreditsException;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Class TransactionService
 *
 * @package Modules\CreditPayment\Services
 */
class TransactionService implements TransactionServiceInterface
{
    public function topUp(User $user, int $amount, string $source = 'manual', ?string $comment = null): void
    {
        $user->increment('credit_balance', $amount);
    }

    public function spend(User $user, int $amount): void
    {
        if ($user->credit_balance < $amount) {
            throw new InsufficientCreditsException(__('Not enough credits'));
        }

        $user->decrement('credit_balance', $amount);
    }

    public function refund(User $user, int $amount, string $comment = null): void
    {
        $user->increment('credit_balance', $amount);
    }

    public function adjust(User $user, int $amount, string $comment = null): void
    {
        $user->update(['credit_balance' => $user->credit_balance + $amount]);
    }

    public function calculateBalanceWithSubscription(User $user, SubscriptionPlan $plan): void
    {
        $currentBalance = $user->credit_balance;

        // 1. Write off remaining credits if any
        if ($currentBalance > 0) {
            $this->adjust(
                user: $user,
                amount: -$currentBalance,
                comment: 'Remaining credits expired due to subscription reset'
            );
        }

        // 2. Assign new credits from the subscription plan
        $this->topUp(
            user: $user,
            amount: $plan->credits,
            source: 'subscription',
            comment: 'Credits assigned from subscription plan'
        );
    }
}
