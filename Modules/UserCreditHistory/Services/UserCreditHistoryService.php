<?php
declare(strict_types=1);

namespace Modules\UserCreditHistory\Services;

use Modules\BookingCreditHistory\Models\BookingCreditHistory;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;
use Modules\UserCreditHistory\Models\UserCreditHistoryInterface;
use Modules\UserCreditHistory\Models\UserCreditHistory;

/**
 * Class CreditBalanceService
 *
 * @package App\Services
 */
class UserCreditHistoryService implements UserCreditHistoryInterface
{
    public function getBalance(User $user): int
    {
        return $user->credit_balance;
    }

    public function topUp(User $user, int $amount, string $source = 'manual', ?string $comment = null): void
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
