<?php

namespace Modules\Order\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Contracts\PaymentInterface;
use Modules\User\Models\User;

/**
 * Interface QuoteInterface
 *
 * @package Modules\Order\Contracts
 */
interface QuoteInterface
{
    public function getUser(): User;
    public function setUser(User $user);

    public function getAmount();
    public function setAmount(int $amount);

    public function getDescription();
    public function getSourceType();

    public function getSourceId();
    public function setSourceId(int $sourceId);

    public function getModel(): Model;
    public function setModel(Model $model);

    public function getPayment();
    public function setPayment(PaymentInterface $payment);

    public function getPaymentMethodConfig();

    public function validate();
//    public function save(): \Illuminate\Database\Eloquent\Model;
}
