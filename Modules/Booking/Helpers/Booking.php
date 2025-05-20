<?php

namespace Modules\Booking\Helpers;

use Modules\Base\Services\CustomerTimezone;
use Modules\User\Models\User;

class Booking
{
    public static function formatDate($date, $format)
    {
        $timezone = self::getCustomerTimezone();

        return $timezone->date($date)->format($format);
    }

    public static function getUserGMT(User $user)
    {
        $timezone = self::getCustomerTimezone();

        return sprintf('GMT%s', $timezone->now($user->timeZoneId)->format('P'));
    }

    private static function getCustomerTimezone(): CustomerTimezone
    {
        return app()->make(CustomerTimezone::class);
    }
}
