<?php
declare(strict_types=1);

namespace Modules\User\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\User\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    /**
     * Configuration path to customer password minimum length
     */
    public const CONFIG_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER = 'password.required_character_classes_number';
    public const CONFIG_PATH_MINIMUM_PASSWORD_LENGTH = 'password.minimum_password_length';

    public const XML_PATH_IS_CONFIRM = 'customer.create_account.confirm';

    protected $pathPrefix = 'customer.';

    public function getRequiredCharClassesNumber(): int
    {
        return (int) $this->getValue(self::CONFIG_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }

    public function getMinPasswordLength(): int
    {
        return (int) $this->getValue(self::CONFIG_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    public function isConfirmationRequired(): bool
    {
        return (bool) $this->getValue(self::XML_PATH_IS_CONFIRM);
    }
}
