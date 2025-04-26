<?php
declare(strict_types=1);

namespace App\Services;

use Modules\User\Models\User;

/**
 * Class CreditBalanceService
 *
 * @package App\Services
 */
class CreditBalanceService
{
    public function updateUserCreditBalance(User $user, int $credits): void
    {
        $user->update(['credit_balance' => $credits]);
    }

//    public function updateUserCreditBalance1(User $user): void
//    {
//        $credited = $user->creditTopUps()->sum('credits_amount');
//        $spent = $user->creditHistory()->sum('credits_amount');
//
//        $user->update(['credit_balance' => $credited - $spent]);
//    }
//
//    public function applySubscriptionPlan(User $user, SubscriptionPlan $plan): void
//    {
//        $user->creditTopUps()->create([
//            'credits_amount' => $plan->credits,
//            'source'         => 'subscription',
//            'comment'        => 'Credits assigned from subscription plan change',
//        ]);
//
//        $this->updateUserCreditBalance($user);
//    }
}
