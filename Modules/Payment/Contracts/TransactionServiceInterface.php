<?php

namespace Modules\Payment\Contracts;

use Modules\User\Models\User;

/**
 * Interface TransactionServiceInterface
 *
 * @package Modules\Booking\Services
 */
interface TransactionServiceInterface
{
    public function topUp(User $user, int $amount, string $source, ?string $comment = null): void;

    public function spend(User $user, int $amount): void;

    public function refund(User $user, int $amount, string $comment = null): void;

    public function adjust(User $user, int $amount, string $comment = null): void;

    public function replace(User $user, int $amount, string $comment = null): void;
}
