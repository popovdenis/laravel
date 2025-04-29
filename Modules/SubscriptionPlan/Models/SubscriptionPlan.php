<?php

namespace Modules\SubscriptionPlan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\UserSubscriptionPlan\Models\UserSubscriptionPlan;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'frequency',
        'frequency_unit',
        'enable_trial',
        'trial_days',
        'price',
        'credits',
        'enable_initial_fee',
        'initial_fee_type',
        'initial_fee_amount',
        'enable_discount',
        'discount_type',
        'discount_amount',
        'sort_order',
    ];

    protected $casts = [
        'status'              => 'boolean',
        'enable_trial'        => 'boolean',
        'trial_days'          => 'integer',
        'price'               => 'decimal:4',
        'credits'             => 'integer',
        'enable_initial_fee'  => 'boolean',
        'initial_fee_amount'  => 'decimal:4',
        'enable_discount'     => 'boolean',
        'discount_amount'     => 'decimal:4',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
