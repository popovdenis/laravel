<?php

namespace Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Order\Models\Order;
use Modules\User\Models\User;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription implements PurchasableInterface
{
    const PAYMENT_METHOD_CONFIG_PATH = 'subscription.applicable_payment_method';

    protected $fillable = [
        'plan_id',
        'user_id',
        'type',
        'credits_amount',
        'credits_amount',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'comment',
        'canceled_at',
        'updated_at',
    ];

    protected $casts = [
        'canceled_immediately' => 'boolean',
        'trial_ends_at'        => 'datetime',
        'starts_at'            => 'datetime',
        'ends_at'              => 'datetime',
        'canceled_at'          => 'datetime',
        'quantity'             => 'integer',
    ];

    public function getMorphClass()
    {
        return self::class;
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Order::class, 'purchasable');
    }

    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'purchasable');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(\Modules\SubscriptionPlan\Models\SubscriptionPlan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsPending()
    : void
    {
        // TODO: Implement markAsPending() method.
    }

    public function markAsConfirmed()
    : void
    {
        // TODO: Implement markAsConfirmed() method.
    }

    public function markAsCancelled()
    : void
    {
        // TODO: Implement markAsCancelled() method.
    }

    public function getPaymentMethod(): string
    {
        return setting(self::PAYMENT_METHOD_CONFIG_PATH);
    }
}
