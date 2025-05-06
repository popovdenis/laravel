<?php

namespace Modules\SubscriptionPlan\Contracts;

/**
 * Interface SubscriptionPlanInterface
 *
 * @package Modules\SubscriptionPlan\Contracts
 */
interface SubscriptionPlanInterface
{
    public function isEnabledTrial();
    public function getTrialDays();
    public function getTransactionPriceId();
    public function getCredits();
    public function isEnabledInitialFree();
    public function getInitialFeeType();
    public function getInitialFeeAmount();
    public function isEnabledDiscount();
    public function getDiscountType();
    public function getDiscountAmount();
}
