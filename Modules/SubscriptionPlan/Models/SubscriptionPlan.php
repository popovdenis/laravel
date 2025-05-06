<?php

namespace Modules\SubscriptionPlan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Subscription\Models\Subscription;
use Modules\SubscriptionPlan\Contracts\SubscriptionPlanInterface;
use Modules\SubscriptionPlan\Enums\InitialFeeTypeEnum;
use Modules\SubscriptionPlan\Enums\DiscountTypeEnum;

class SubscriptionPlan extends Model implements SubscriptionPlanInterface
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
        'transaction_price_id',
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
        'initial_fee_type'    => InitialFeeTypeEnum::class,
        'discount_type'       => DiscountTypeEnum::class
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function isEnabledTrial()
    {
        return !empty($this->enable_trial);
    }

    public function getTrialDays()
    {
        return (int) $this->trial_days;
    }

    public function getTransactionPriceId()
    {
        return $this->transaction_price_id;
    }

    public function getCredits()
    {
        return $this->credits;
    }

    public function isEnabledInitialFree()
    {
        return !empty($this->enable_initial_fee);
    }

    public function getInitialFeeType(): InitialFeeTypeEnum
    {
        return $this->initial_fee_type;
    }

    public function getInitialFeeAmount()
    {
        return $this->initial_fee_amount;
    }

    public function isEnabledDiscount()
    {
        return !empty($this->enable_discount);
    }

    public function getDiscountType(): DiscountTypeEnum
    {
        return $this->discount_type;
    }

    public function getDiscountAmount()
    {
        return $this->discount_amount;
    }
}
