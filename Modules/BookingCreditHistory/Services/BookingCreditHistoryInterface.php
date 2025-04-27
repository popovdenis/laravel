<?php

namespace Modules\BookingCreditHistory\Services;

use Modules\BookingCreditHistory\Models\BookingCreditHistory;
use Modules\User\Models\User;

/**
 * Interface BookingCreditHistoryInterface
 *
 * @package Modules\Booking\Services
 */
interface BookingCreditHistoryInterface
{
    public function topUp(User $user, int $amount, string $source, ?string $comment = null): void;

    public function spend(User $user, int $amount): void;

    public function refund(User $user, int $amount, string $comment = null): void;

    public function adjust(User $user, int $amount, string $comment = null): void;
}
