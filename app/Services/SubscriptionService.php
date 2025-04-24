<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
 * Class SubscriptionService
 *
 * @package App\Services
 */
class SubscriptionService
{
    public function syncForUser(User $user, ?int $planId): void
    {
        $currentPlanId = $user->subscription?->plan_id;

        if ($planId === $currentPlanId) {
            // No changes, do nothing.
            return;
        }

        if ($planId) {
            $user->subscription()->updateOrCreate([], ['plan_id' => $planId]);
        } else {
            $user->subscription()?->delete();
        }
    }
}
