<?php
/**
 * @package App\Models\Enums
 */

namespace App\Models\Enums;

enum FrequencyUnit: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';

    public function label(): string
    {
        return match ($this) {
            self::DAY => 'Day',
            self::WEEK => 'Week',
            self::MONTH => 'Month',
            self::YEAR => 'Year',
        };
    }

    public static function getPluralUnit($unit): string
    {
        return match ($unit) {
            self::DAY    => 'days',
            self::WEEK   => 'weeks',
            self::MONTH  => 'months',
            self::YEAR   => 'years',
            default  => $unit,
        };
    }
}
