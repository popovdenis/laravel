<?php
declare(strict_types=1);

namespace Modules\Base\Utils;

/**
 * Class StringUtils
 *
 * @package Modules\Base\Utils
 */
class StringUtils
{
    /**
     * Default charset
     */
    public const ICONV_CHARSET = 'UTF-8';

    /**
     * Retrieve string length using default charset
     *
     * @param string $string
     * @return int
     */
    public function strlen($string)
    {
        return $string !== null ? mb_strlen($string, self::ICONV_CHARSET) : 0;
    }
}
