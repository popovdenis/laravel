<?php

namespace Modules\User\Contracts;

use Modules\User\Data\CustomerData;

/**
 * Interface AccountManagementInterface
 *
 * @package Modules\User\Contracts
 */
interface AccountManagementInterface
{
    public const ACCOUNT_CONFIRMED = 'account_confirmed';
    public const ACCOUNT_CONFIRMATION_REQUIRED = 'account_confirmation_required';
    public const ACCOUNT_CONFIRMATION_NOT_REQUIRED = 'account_confirmation_not_required';
    public const MAX_PASSWORD_LENGTH = 256;

    public function createAccount(CustomerData $customer, $password = null);
}
