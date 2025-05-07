<?php
declare(strict_types=1);

namespace Modules\User\Models\Customer;

use Modules\Base\Exceptions\InputException;

/**
 * Class CredentialsValidator
 *
 * @package Modules\User\Models\Customer
 */
class CredentialsValidator
{
    /**
     * Check that password is different from email.
     *
     * @param string $email
     * @param string $password
     *
     * @return void
     *
     * @throws InputException
     */
    public function checkPasswordDifferentFromEmail($email, $password)
    {
        if (strcasecmp($password, $email) == 0) {
            throw new InputException(
                "The password can't be the same as the email address. Create a new password and try again."
            );
        }
    }
}
