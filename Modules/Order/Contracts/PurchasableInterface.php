<?php

namespace Modules\Order\Contracts;

use Modules\User\Models\User;

/**
 * Interface PurchasableInterface
 *
 * @package Modules\Order\Contracts
 */
interface PurchasableInterface
{
    public function markAsPending(): void;
    public function markAsConfirmed(): void;
    public function markAsCancelled(): void;
    public function getPaymentMethod(): string;
}
