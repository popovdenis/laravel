<?php
declare(strict_types=1);

namespace Modules\Base\Models;

/**
 * Class ConfigProvider
 *
 * @package Modules\Base\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    public const XML_PATH_DEFAULT_TIMEZONE = 'locale.timezone';

    protected $pathPrefix = 'base.';

    public function getDefaultTimeZone()
    {
        return $this->getValue(self::XML_PATH_DEFAULT_TIMEZONE);
    }
}
