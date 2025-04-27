<?php

namespace Modules\Payment\Models\Transaction;

use Modules\Booking\Models\Booking;
use Modules\BookingCreditHistory\Models\Enums\BookingAction;
use Modules\Payment\Models\Enums\PaymentMethod;
use Modules\User\Models\User;

/**
 * Interface ManagerInterface
 *
 * @package Modules\Payment\Models\Transaction
 */
interface ManagerInterface
{
    public function getTransaction($transactionId, User $user);

    public function generateTransactionId(User $user, int $amount, PaymentMethod $method, BookingAction $action, string $comment = null): int;
}
