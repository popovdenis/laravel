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
    public function getAmount(): int;
    public function getDescription(): string;
    public function getSourceType(): string;

    public function getSourceId(): int;
    public function setSourceId(int $sourceId);

    public function getModel(): Model;
    public function setModel(Model $model);

    public function getPaymentMethodConfig(): string;

    public function getPayment();
    public function setPayment(PaymentInterface $payment);

    public function validate(): void;
//    public function save(): \Illuminate\Database\Eloquent\Model;
}
