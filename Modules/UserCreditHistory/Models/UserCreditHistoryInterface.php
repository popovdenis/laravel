<?php

namespace Modules\UserCreditHistory\Models;

use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;

/**
 * Interface UserCreditHistoryInterface
 *
 * @package Modules\UserCreditHistory\Models
 */
interface UserCreditHistoryInterface
{
    public function calculateBalanceWithSubscription(User $user, SubscriptionPlan $plan): void;

    public function topUp(User $user, int $amount, string $source, ?string $comment = null): void;

    public function adjust(User $user, int $amount, string $comment = null): void;
}
