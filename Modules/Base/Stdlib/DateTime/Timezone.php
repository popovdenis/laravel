<?php
declare(strict_types=1);

namespace Modules\Base\Stdlib\DateTime;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Base\Conracts\TimezoneInterface;
use Modules\Base\Models\ConfigProvider;
use Modules\Base\Stdlib\DateTime;
use Modules\Base\Stdlib\DateTime\Intl\DateFormatterFactory;

/**
 * Class Timezone
 *
 * @package Modules\Base\Stdlib\DateTime
 */
class Timezone implements TimezoneInterface
{
    private string $defaultTimezonePath;
    private DateFormatterFactory $dateFormatterFactory;
    private ConfigProvider $configProvider;
    private string $currentLocale;
    private DateTime $dateTime;

    public function __construct(
        DateFormatterFactory $dateFormatterFactory,
        ConfigProvider $configProvider,
        DateTime $dateTime,
        string $defaultTimezonePath = ConfigProvider::XML_PATH_DEFAULT_TIMEZONE,
    )
    {
        $this->currentLocale = \Illuminate\Support\Facades\App::getLocale();
        $this->defaultTimezonePath = $defaultTimezonePath;
        $this->dateFormatterFactory = $dateFormatterFactory;
        $this->configProvider = $configProvider;
        $this->dateTime = $dateTime;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultTimezonePath()
    {
        return $this->defaultTimezonePath;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultTimezone()
    {
        return 'UTC';
    }

    /**
     * @inheritdoc
     */
    public function getConfigTimezone()
    {
        return $this->configProvider->getValue(
            $this->getDefaultTimezonePath()
        );
    }

    /**
     * @inheritdoc
     */
    public function getDateFormat($type = \IntlDateFormatter::SHORT)
    {
        $formatter = $this->dateFormatterFactory->create(
            $this->currentLocale,
            (int) $type,
            \IntlDateFormatter::NONE,
            null,
            false
        );

        return $formatter->getPattern();
    }

    /**
     * @inheritdoc
     */
    public function getDateFormatWithLongYear()
    {
        $formatter = $this->dateFormatterFactory->create(
            $this->currentLocale,
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::NONE
        );

        return $formatter->getPattern();
    }

    /**
     * @inheritdoc
     */
    public function getTimeFormat($type = \IntlDateFormatter::SHORT)
    {
        $formatter = $this->dateFormatterFactory->create(
            $this->currentLocale,
            \IntlDateFormatter::NONE,
            (int)$type
        );

        return $formatter->getPattern();
    }

    /**
     * @inheritdoc
     */
    public function getDateTimeFormat($type)
    {
        return $this->getDateFormat($type) . ' ' . $this->getTimeFormat($type);
    }

    public function date($date = null, ?string $locale = null, bool $useTimezone = true, bool $includeTime = true): Carbon
    {
        $locale = $locale ?? app()->getLocale();
        $timezone = $useTimezone ? $this->getConfigTimezone() : config('app.timezone', 'UTC');

        if (empty($date)) {
            return Carbon::now($timezone);
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date)->setTimezone($timezone);
        }

        if (!is_numeric($date)) {
            $date = $this->appendTimeIfNeeded((string) $date, $includeTime, $timezone, $locale);
            return Carbon::parse($date, $timezone);
        }

        return Carbon::createFromTimestamp($date, $timezone);
    }

    public function scopeDate($scope = null, $date = null, bool $includeTime = false): Carbon
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

    /**
     * @inheritdoc
     */
    public function formatDate($date = null, string $format = 'Y-m-d', bool $showTime = false, ?string $timezone = null): string
    {
        if ($showTime) {
            $format .= ' H:i:s';
        }

        return $this->formatDateTime($date, $format, $timezone);
    }

    /**
     * @inheritdoc
     */
    public function scopeTimeStamp()
    {
        $timezone = $this->configProvider->getValue($this->getDefaultTimezonePath());
        $currentTimezone = @date_default_timezone_get();
        @date_default_timezone_set($timezone);
        $date = date('Y-m-d H:i:s');
        @date_default_timezone_set($currentTimezone);
        return strtotime($date);
    }

    /**
     * @inheritdoc
     */
    public function isScopeDateInInterval($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? '';
        $dateTo = $dateTo ?? '';


        $timeStamp = $this->scopeTimeStamp();
        $fromTimeStamp = strtotime($dateFrom);
        $toTimeStamp = strtotime($dateTo);
        if ($dateTo) {
            // fix date YYYY-MM-DD 00:00:00 to YYYY-MM-DD 23:59:59
            $toTimeStamp += 86400;
        }

        return ! (! $this->dateTime->isEmptyDate($dateFrom) && $timeStamp < $fromTimeStamp ||
            ! $this->dateTime->isEmptyDate($dateTo) && $timeStamp > $toTimeStamp);
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

        if ($carbonDate->timezone->getName() !== $configTimezone) {
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
     * @param string $locale
     * @return string
     * @throws ValidationException
     */
    private function appendTimeIfNeeded(string $date, bool $includeTime, string $timezone, string $locale)
    {
        if ($includeTime && !preg_match('/\d{1}:\d{2}/', $date)) {
            $formatter = $this->dateFormatterFactory->create(
                $locale,
                \IntlDateFormatter::SHORT,
                \IntlDateFormatter::NONE,
                $timezone
            );
            $timestamp = $formatter->parse($date);
            if (!$timestamp) {
                throw ValidationException::withMessages([
                    'field' => __('Could not append time to DateTime'),
                ]);
            }

            $formatterWithHour = $this->dateFormatterFactory->create(
                $locale,
                \IntlDateFormatter::SHORT,
                \IntlDateFormatter::SHORT,
                $timezone
            );
            $date = $formatterWithHour->format($timestamp);
        }

        return $date;
    }
}
