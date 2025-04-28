<?php

namespace Modules\Payment\Models\Transaction;

use Modules\BookingCreditHistory\Enums\BookingAction;
use Modules\Payment\Enums\PaymentMethod;
use Modules\User\Models\User;

/**
 * Interface ManagerInterface
 *
 * @package Modules\Payment\Models\Transaction
 */
interface ManagerInterface
{
    public function getTransaction($transactionId, User $user);

    public function generateTransactionId(User $user, int $amount, string $method, BookingAction $action, string $comment = null): int;
}
