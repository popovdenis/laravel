<?php
declare(strict_types=1);

namespace Modules\Base\Stdlib\DateTime;

use Carbon\Carbon;
use Modules\Base\Conracts\TimezoneInterface;
use Modules\Base\Models\ConfigProvider;
use Modules\Base\Stdlib\DateTime;

/**
 * Class Timezone
 *
 * @package Modules\Base\Stdlib\DateTime
 */
class Timezone implements TimezoneInterface
{
    private string $defaultTimezonePath;
    private ConfigProvider $configProvider;
    private DateTime $dateTime;

    public function __construct(
        ConfigProvider $configProvider,
        DateTime $dateTime,
        string $defaultTimezonePath = ConfigProvider::XML_PATH_DEFAULT_TIMEZONE,
    )
    {
        $this->defaultTimezonePath = $defaultTimezonePath;
        $this->configProvider = $configProvider;
        $this->dateTime = $dateTime;
    }

    public function getDefaultTimezonePath()
    {
        return $this->defaultTimezonePath;
    }

    public function getDefaultTimezone()
    {
        return 'UTC';
    }

    public function getConfigTimezone()
    {
        return $this->configProvider->getValue(
            $this->getDefaultTimezonePath()
        );
    }

    public function getDateFormat($type = \IntlDateFormatter::SHORT): string
    {
        return match ($type) {
            \IntlDateFormatter::FULL => 'l, j F Y',
            \IntlDateFormatter::LONG => 'j F Y',
            \IntlDateFormatter::MEDIUM => 'j M Y',
            \IntlDateFormatter::SHORT => 'd/m/Y',
            default => 'Y-m-d',
        };
    }

    public function getDateFormatWithLongYear(): string
    {
        return str_replace('y', 'Y', $this->getDateFormat());
    }

    public function getTimeFormat($type = \IntlDateFormatter::SHORT): string
    {
        return match ($type) {
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::LONG => 'H:i:s',
            \IntlDateFormatter::MEDIUM => 'H:i',
            \IntlDateFormatter::SHORT => 'H:i',
            default => 'H:i',
        };
    }

    public function getDateTimeFormat($type)
    {
        return $this->getDateFormat($type) . ' ' . $this->getTimeFormat($type);
    }

    public function date($date = null, ?string $locale = null, bool $useTimezone = true, bool $includeTime = true): Carbon
    {
        $timezone = $useTimezone ? $this->getConfigTimezone() : config('app.timezone', 'UTC');

        if (empty($date)) {
            return Carbon::now($timezone);
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date)->setTimezone($timezone);
        }

        if (!is_numeric($date)) {
            $date = $this->appendTimeIfNeeded((string) $date, $includeTime, $timezone);
            return Carbon::parse($date, $timezone);
        }

        return Carbon::createFromTimestamp($date, $timezone);
    }

    public function scopeDate($date = null, bool $includeTime = false): Carbon
    {
        $timezone = $this->getConfigTimezone();

        if (empty($date)) {
            $date = Carbon::now($timezone);
        } elseif ($date instanceof \DateTimeInterface) {
            $date = Carbon::instance($date)->setTimezone($timezone);
        } else {
            $date = is_numeric($date)
                ? Carbon::createFromTimestamp($date, $timezone)
                : Carbon::parse($date, $timezone);
        }

        if (!$includeTime) {
            $date->setTime(0, 0, 0);
        }

        return $date;
    }

    public function formatDate(
        $date = null,
        string $format = 'Y-m-d',
        bool $showTime = false,
        ?string $timezone = null
    ): string
    {
        if ($showTime) {
            $format .= ' H:i:s';
        }

        return $this->formatDateTime($date, $format, $timezone);
    }

    public function scopeTimeStamp()
    {
        $timezone = $this->configProvider->getValue($this->getDefaultTimezonePath());

        return Carbon::now($timezone)->timestamp;
    }

    public function isScopeDateInInterval($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? '';
        $dateTo = $dateTo ?? '';

        $timeStamp = $this->scopeTimeStamp();
        $fromTimeStamp = $dateFrom ? Carbon::parse($dateFrom)->timestamp : null;
        $toTimeStamp = $dateTo ? Carbon::parse($dateTo)->addDay()->timestamp : null;

        return !(
            (!$this->dateTime->isEmptyDate($dateFrom) && $timeStamp < $fromTimeStamp) ||
            (!$this->dateTime->isEmptyDate($dateTo) && $timeStamp > $toTimeStamp)
        );
    }

    public function formatDateTime(
        $date,
        string $format = 'Y-m-d H:i:s',
        ?string $timezone = null
    ): string
    {
        $carbonDate = $date instanceof \DateTimeInterface
            ? Carbon::instance($date)
            : Carbon::parse($date);

        if ($timezone) {
            $carbonDate->setTimezone($timezone);
        }

        return $carbonDate->format($format);
    }

    public function convertConfigTimeToUtc($date, string $format = 'Y-m-d H:i:s'): string
    {
        $configTimezone = $this->getConfigTimezone();

        $carbonDate = $date instanceof \DateTimeInterface
            ? Carbon::instance($date)
            : Carbon::parse($date, $configTimezone);

        if ($carbonDate->timezone->getName() !== (new \DateTimeZone($configTimezone))->getName()) {
            throw new \Exception("The DateTime object timezone must be {$configTimezone}.");
        }

        return $carbonDate->setTimezone('UTC')->format($format);
    }

    /**
     * Append time to DateTime
     *
     * @param string $date
     * @param boolean $includeTime
     * @param string $timezone
     * @return string
     * @throws \Exception
     */
    private function appendTimeIfNeeded(string $date, bool $includeTime, string $timezone)
    {
        if ($includeTime && !preg_match('/^\d{1,2}:\d{2}$/', $date)) {
            $parsed = Carbon::parse($date, $timezone);
            return $parsed->format('Y-m-d H:i:s');
        }

        return $date;
    }
}
