<?php
declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Exceptions\AlreadyExistsException;
use Modules\Base\Exceptions\InputException;
use Modules\Base\Utils\StringUtils;
use Modules\User\Contracts\AccountManagementInterface;
use Modules\User\Data\CustomerData;
use Modules\User\Exceptions\CreateAccountException;
use Modules\User\Models\Customer\CredentialsValidator;

/**
 * Class AccountManager
 *
 * @package Modules\User\Models
 */
class AccountManager implements AccountManagementInterface
{
    private CredentialsValidator $credentialsValidator;
    private StringUtils $stringUtils;
    private ConfigProvider $configProvider;

    public function __construct(
        CredentialsValidator $credentialsValidator,
        StringUtils $stringUtils,
        ConfigProvider $configProvider
    )
    {
        $this->credentialsValidator = $credentialsValidator;
        $this->stringUtils = $stringUtils;
        $this->configProvider = $configProvider;
    }

    /**
     * @throws CreateAccountException
     * @throws AlreadyExistsException
     */
    public function createAccount(CustomerData $customer, $password = null)
    {
        if ($password !== null) {
            $this->checkPasswordStrength($password);
            $customerEmail = $customer->email;
            try {
                $this->credentialsValidator->checkPasswordDifferentFromEmail($customerEmail, $password);
            } catch (InputException $e) {
                throw new CreateAccountException(
                    "The password can't be the same as the email address. Create a new password and try again."
                );
            }
            $hash = $this->createPasswordHash($password);
        } else {
            $hash = null;
        }

        return $this->createAccountWithPasswordHash($customer, $hash);
    }

    public function getConfirmationStatus($customerId)
    {
        // load customer by id
        $customer = User::where('id', $customerId);

        return $this->isConfirmationRequired($customer)
            ? $customer->confirmation ? self::ACCOUNT_CONFIRMATION_REQUIRED : self::ACCOUNT_CONFIRMED
            : self::ACCOUNT_CONFIRMATION_NOT_REQUIRED;
    }

    protected function isConfirmationRequired($customer): bool
    {
        return $this->configProvider->isConfirmationRequired($customer);
    }

    /**
     * Create a hash for the given password
     *
     * @param string $password
     * @return string
     */
    protected function createPasswordHash($password)
    {
        return Hash::make($password);
    }

    /**
     * Make sure that password complies with minimum security requirements.
     *
     * @param string $password
     * @return void
     * @throws CreateAccountException
     */
    protected function checkPasswordStrength($password)
    {
        $length = $this->stringUtils->strlen($password);
        if ($length > self::MAX_PASSWORD_LENGTH) {
            throw new CreateAccountException(
                sprintf(
                    'Please enter a password with at most %s characters.',
                    self::MAX_PASSWORD_LENGTH
                )
            );
        }
        $configMinPasswordLength = $this->configProvider->getMinPasswordLength();
        if ($length < $configMinPasswordLength) {
            throw new CreateAccountException(
                sprintf(
                    'The password needs at least %s characters. Create a new password and try again.',
                    $configMinPasswordLength
                )
            );
        }
        $trimmedPassLength = $this->stringUtils->strlen($password === null ? '' : trim($password));
        if ($trimmedPassLength != $length) {
            throw new CreateAccountException(
                "The password can't begin or end with a space. Verify the password and try again."
            );
        }

        $requiredCharactersCheck = $this->makeRequiredCharactersCheck($password);
        if ($requiredCharactersCheck !== 0) {
            throw new CreateAccountException(
                sprintf(
                    'Minimum of different classes of characters in password is %s.' .
                    ' Classes of characters: Lower Case, Upper Case, Digits, Special Characters.',
                    $requiredCharactersCheck
                )
            );
        }
    }

    /**
     * Check password for presence of required character sets
     *
     * @param string $password
     * @return int
     */
    protected function makeRequiredCharactersCheck($password)
    {
        $counter = 0;
        $requiredNumber = $this->configProvider->getRequiredCharClassesNumber();
        $return = 0;

        if ($password !== null) {
            if (preg_match('/[0-9]+/', $password)) {
                $counter++;
            }
            if (preg_match('/[A-Z]+/', $password)) {
                $counter++;
            }
            if (preg_match('/[a-z]+/', $password)) {
                $counter++;
            }
            if (preg_match('/[^a-zA-Z0-9]+/', $password)) {
                $counter++;
            }
        }

        if ($counter < $requiredNumber) {
            $return = $requiredNumber;
        }

        return $return;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function createAccountWithPasswordHash($customer, $hash)
    {
        // This logic allows an existing customer to be added to a different store.  No new account is created.
        // The plan is to move this logic into a new method called something like 'registerAccountWithStore'
        if (User::where('email', $customer->email)->exists()) {
            throw new AlreadyExistsException(
                'A customer with the same email address already exists.'
            );
        }

        try {
            // If customer exists, existing hash will be used by Repository
            $customer = User::create([
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'email' => $customer->email,
                'password' => $hash,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        $this->sendEmailConfirmation($customer);

        return $customer;
    }

    /**
     * Send either confirmation or welcome email after an account creation
     *
     * @param $customer
     *
     * @return void
     */
    protected function sendEmailConfirmation($customer)
    {
        // TODO
    }
}
