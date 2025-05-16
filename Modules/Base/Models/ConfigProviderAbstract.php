<?php
declare(strict_types=1);

namespace Modules\Base\Models;

/**
 * Class ConfigProviderAbstract
 *
 * @package Modules\Base\Models
 */
class ConfigProviderAbstract
{
    /**
     * xpath prefix of module (section)
     * @var string '{section}/'
     */
    protected $pathPrefix = '/';

    /**
     * ConfigProviderAbstract constructor.
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        if ($this->pathPrefix === '/') {
            throw new \LogicException('$pathPrefix should be declared');
        }
    }

    public function getValue(string|array $key = '*', mixed $default = null)
    {
        if (is_array($key)) {
            $settings = [];

            foreach ($key as $k => $v) {
                data_set($settings, $k, \Outerweb\Settings\Models\Setting::set($k, $v));
            }

            return $settings;
        }

        $value = \Outerweb\Settings\Models\Setting::get($this->pathPrefix . $key, $default);
        if (empty($value)) {
            $value = config($this->pathPrefix . $key);
        }

        return $value;
    }

    public function setValue(string|array $key, mixed $value): void
    {
        setting([$this->pathPrefix . $key => $value]);
    }
}
