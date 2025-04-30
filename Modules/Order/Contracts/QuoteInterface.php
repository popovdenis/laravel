<?php

namespace Modules\Order\Contracts;

use Modules\User\Models\User;

/**
 * Interface QuoteInterface
 *
 * @package Modules\Order\Contracts
 */
interface QuoteInterface
{
    public function getUser(): User;
    public function getAmount(): int;
    public function getDescription(): string;
    public function getSourceType(): string;
    public function getSourceId(): int;

    public function getPaymentMethodConfig(): string;

    public function validate(): void;
//    public function save(): \Illuminate\Database\Eloquent\Model;
}
