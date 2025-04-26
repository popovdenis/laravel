<?php
declare(strict_types=1);

namespace Modules\BookingCreditHistory\Services;

use Modules\Booking\Models\BookingInterface;
use Modules\BookingCreditHistory\Models\BookingCreditHistory;
use Modules\BookingCreditHistory\Models\Enums\BookingAction;
use Modules\User\Models\User;
use Modules\UserCreditHistory\Exceptions\InsufficientCreditsException;
use Modules\UserCreditHistory\Models\UserCreditHistory;

/**
 * Class BookingCreditHistoryService
 *
 * @package Modules\Booking\Services
 */
class BookingCreditHistoryService implements BookingCreditHistoryInterface
{
    public function topUp(User $user, int $amount, string $source = 'manual', ?string $comment = null): void
    {
        $user->increment('credit_balance', $amount);

        UserCreditHistory::create([
            'user_id'        => $user->id,
            'credits_amount' => $amount,
            'source'         => $source,
            'comment'        => $comment,
        ]);
    }

    public function spend(User $user, int $amount): void
    {
        if ($user->credit_balance < $amount) {
            throw new InsufficientCreditsException(__('Not enough credits'));
        }

        $user->decrement('credit_balance', $amount);
    }

    public function logTransaction(User $user, int $amount, string $comment = null): BookingCreditHistory
    {
        return BookingCreditHistory::create([
            'user_id'        => $user->id,
            'credits_amount' => -$amount,
            'action'         => BookingAction::SPEND,
            'comment'        => $comment,
        ]);
    }

    public function refund(User $user, int $amount, string $comment = null): void
    {
        $user->increment('credit_balance', $amount);

        BookingCreditHistory::create([
            'user_id'        => $user->id,
            'credits_amount' => $amount,
            'action'         => 'refund',
            'comment'        => $comment,
        ]);
    }

    public function adjust(User $user, int $amount, string $comment = null): void
    {
        $user->update(['credit_balance' => $user->credit_balance + $amount]);

        BookingCreditHistory::create([
            'user_id'        => $user->id,
            'credits_amount' => $amount,
            'action'         => 'adjustment',
            'comment'        => $comment,
        ]);
    }
}
