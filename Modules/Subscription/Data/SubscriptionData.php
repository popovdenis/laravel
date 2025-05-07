<?php
declare(strict_types=1);

namespace Modules\Subscription\Data;

use Illuminate\Http\Request;
use Modules\Payment\Contracts\RequestDataInterface;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Models\User;
use Spatie\LaravelData\Data;

/**
 * Class SubscriptionData
 *
 * @package Modules\Subscription\Data
 */
class SubscriptionData extends Data implements RequestDataInterface
{
    public function __construct(
        public User $student,
        public int $planId,
        public SubscriptionPlan $plan,
        public ?int $amount,
        public ?string $transactionPriceId,
        public ?string $method,
        public array $extra = []
    ) {
    }

    public static function rules(): array
    {
        return [
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ];
    }

    public static function fromRequest(Request $request): static
    {
        $newPlan = SubscriptionPlan::findOrFail($request->plan_id);

        return static::from([
            'student' => auth()->user(),
            'planId' => $request->input('plan_id') ?? null,
            'amount' => $newPlan->price,
            'plan' => $newPlan,
            'credits' => $newPlan->credits,
            'transactionPriceId' => $newPlan->transaction_price_id,
            'method' => setting('subscription.applicable_payment_method')
        ]);
    }

    public static function fromModel(User $user, int $planId): static
    {
        $newPlan = SubscriptionPlan::findOrFail($planId);

        return static::from([
            'student' => $user,
            'planId' => $newPlan->id,
            'amount' => $newPlan->price,
            'plan' => $newPlan,
            'credits' => $newPlan->credits,
            'transactionPriceId' => $newPlan->transaction_price_id,
            'method' => setting('subscription.applicable_payment_method')
        ]);
    }
}
