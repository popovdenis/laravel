<?php

namespace Modules\Base\Conracts;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * Interface TimezoneInterface
 *
 * @package Modules\Base\Conracts
 */
interface TimezoneInterface
{
    /**
     * Retrieve timezone code
     *
     * @return string
     */
    public function getDefaultTimezone();

    /**
     * Retrieve ISO date format
     *
     * @param   int $type
     * @return  string
     */
    public function getDateFormat($type = \IntlDateFormatter::SHORT);

    /**
     * Retrieve short date format with 4-digit year
     *
     * @return  string
     */
    public function getDateFormatWithLongYear();

    /**
     * Retrieve ISO time format
     *
     * @param   string $type
     * @return  string
     */
    public function getTimeFormat($type = null);

    /**
     * Retrieve ISO datetime format
     *
     * @param   string $type
     * @return  string
     */
    public function getDateTimeFormat($type);

    /**
     * Create \DateTime object for current locale
     *
     * @param mixed $date
     * @param string $locale
     * @param bool $useTimezone
     * @param bool $includeTime
     * @return \DateTime
     */
    public function date($date = null, ?string $locale = null, bool $useTimezone = true, bool $includeTime = true): Carbon;

    /**
     * Create \DateTime object with date converted to scope timezone and scope Locale
     *
     * @param   string|integer|\DateTime|array|null $date date in UTC
     * @param   boolean $includeTime flag for including time to date
     * @return  \DateTime
     */
    public function scopeDate($date = null, bool $includeTime = false): Carbon;

    /**
     * Get scope timestamp
     *
     * Timestamp will be built with scope timezone settings
     *
     * @return  int
     */
    public function scopeTimeStamp();

    /**
     * Format date using current locale options and time zone.
     *
     * @param \DateTime|null $date
     * @param int $format
     * @param bool $showTime
     * @return string
     */
    public function formatDate(
        $date = null,
        string $format = 'Y-m-d',
        bool $showTime = false,
        ?string $timezone = null
    ): string;

    /**
     * Gets the scope config timezone
     *
     * @return string
     */
    public function getConfigTimezone($customerTimezoneId = null);

    /**
     * Checks if current date of the given scope (in the scope timezone) is within the range
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return bool
     */
    public function isScopeDateInInterval($dateFrom = null, $dateTo = null);

    /**
     * Format date according to date and time formats, locale, timezone and pattern.
     *
     * @param string|\DateTimeInterface $date
     * @param string $format
     * @param string|null $locale
     * @param string|null $timezone
     * @return string
     */
    public function formatDateTime(
        $date,
        string $format = 'Y-m-d H:i:s',
        ?string $timezone = null
    );

    /**
     * Convert date from config timezone to UTC.
     *
     * If pass \DateTime object as argument be sure that timezone is the same with config timezone
     *
     * @param string|\DateTimeInterface $date
     * @return Carbon
     * @throws ValidationException
     */
    public function convertConfigTimeToUtc($date): Carbon;
}
