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
            $plan = \App\Models\SubscriptionPlan::find($planId);

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

            $user->subscription()->updateOrCreate([], [
                'plan_id'       => $planId,
                'starts_at'     => $startsAt,
                'ends_at'       => $endsAt,
                'trial_ends_at' => $trialEndsAt,
            ]);
        } else {
            $user->subscription()?->delete();
        }
    }
}
