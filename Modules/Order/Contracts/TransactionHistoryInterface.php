<?php

namespace Modules\Order\Contracts;

/**
 * Interface TransactionHistoryInterface
 *
 * @package Modules\Order\Contracts
 */
interface TransactionHistoryInterface
{
    public function generateTransactionId(OrderInterface $order): int;
}
