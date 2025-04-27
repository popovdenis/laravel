<?php
declare(strict_types=1);

namespace Modules\BookingCreditHistory\Services;

use Modules\User\Models\User;
use Modules\UserCreditHistory\Exceptions\InsufficientCreditsException;

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
    }

    public function spend(User $user, int $amount): void
    {
        if ($user->credit_balance < $amount) {
            throw new InsufficientCreditsException(__('Not enough credits'));
        }

        $user->decrement('credit_balance', $amount);
    }

    public function refund(User $user, int $amount, string $comment = null): void
    {
        $user->increment('credit_balance', $amount);
    }

    public function adjust(User $user, int $amount, string $comment = null): void
    {
        $user->update(['credit_balance' => $user->credit_balance + $amount]);
    }
}
