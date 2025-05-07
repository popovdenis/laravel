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
    public function createAccount(CustomerData $customer, $password = null);
}
