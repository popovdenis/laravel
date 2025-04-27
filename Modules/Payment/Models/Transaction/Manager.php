<?php
declare(strict_types=1);

namespace Modules\Payment\Models\Transaction;

use Modules\Booking\Models\Booking;
use Modules\BookingCreditHistory\Models\BookingCreditHistory;
use Modules\BookingCreditHistory\Models\Enums\BookingAction;
use Modules\Payment\Models\Enums\PaymentMethod;
use Modules\User\Models\User;

/**
 * Class Manager
 *
 * @package Modules\Payment\Models\Transaction
 */
class Manager implements ManagerInterface
{
    public function getTransaction($transactionId, User $user)
    {
        $transaction = false;
        if ($transactionId && $user->id) {
            $transaction = BookingCreditHistory::where('id', $transactionId)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        return $transaction;
    }

    public function generateTransactionId(User $user, int $amount, PaymentMethod $method, BookingAction $action, string $comment = null): int
    {
        $transaction = BookingCreditHistory::create([
            'user_id'        => $user->id,
            'credits_amount' => $amount,
            'payment_method' => $method->value,
            'action'         => $action->value,
            'comment'        => $comment,
        ]);

        return $transaction->id;
    }
}
