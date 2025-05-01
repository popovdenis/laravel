<?php

namespace Modules\Order\Contracts;

use Modules\Payment\Contracts\PaymentInterface;

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

    public function setPayment(PaymentInterface $payment);
    public function getPayment(): PaymentInterface;

    public function setState($state);
    public function getState();

    public function setStatus($status);
    public function getStatus();

    public function setUserId(int $userId): void;
    public function getUserId(): int;

    public function setTotalAmount(int $totalAmount): void;
    public function getTotalAmount();
}
