<?php

namespace Modules\Order\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\User\Models\User;

/**
 * Interface OrderInterface
 *
 * @package Modules\Order\Contracts
 */
interface OrderInterface
{
    public function place(): void;
    public function cancel(): void;

    public function setQuote(QuoteInterface $quote): void;
    public function getQuote(): QuoteInterface;

    public function setPayment(PaymentMethodInterface $method): void;
    public function getPayment(): PaymentMethodInterface;

    public function setUserId(int $userId): void;
    public function getUserId(): int;

    public function setTotalAmount(int $totalAmount): void;
    public function getTotalAmount();
}
