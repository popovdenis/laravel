<?php

namespace App\Filament\Resources\CronScheduleResource\Pages;

use Carbon\Carbon;

/**
 * Trait CronScheduleTrait
 *
 * @package App\Filament\Resources\CronScheduleResource\Pages
 */
trait CronScheduleTrait
{
    public function convertDataBeforeSave(array $data): array
    {
        if (($data['enabled'] ?? false) === false) {
            return $data;
        }

        switch ($data['frequency']) {
            case 'hourly':
                $data['hours'] = null;
                $data['day'] = null;
                $data['day_of_week'] = null;
                $data['minutes'] = 0;
                break;

            case 'daily':
                $data['day'] = null;
                $data['day_of_week'] = null;

                if (!empty($data['schedule_time'])) {
                    $time = Carbon::createFromFormat('H:i', $data['schedule_time']);
                    $data['hours'] = $time->hour;
                    $data['minutes'] = $time->minute;
                }
                break;

            case 'weekly':
                $data['day'] = null;

                if (!empty($data['schedule_time'])) {
                    $time = Carbon::createFromFormat('H:i', $data['schedule_time']);
                    $data['hours'] = $time->hour;
                    $data['minutes'] = $time->minute;
                }

                $data['day_of_week'] = (int) $data['day_of_week'];
                break;

            case 'monthly':
                if (!empty($data['schedule_time'])) {
                    $time = Carbon::createFromFormat('H:i', $data['schedule_time']);
                    $data['hours'] = $time->hour;
                    $data['minutes'] = $time->minute;
                }

                $data['day'] = (int) $data['day']; // или now()->day
                $data['day_of_week'] = null;
                break;

            case 'once':
                if (!empty($data['once_date'])) {
                    $date = Carbon::parse($data['once_date']);
                    $data['day'] = $date->day;
                    $data['day_of_week'] = $date->dayOfWeek;
                    $data['hours'] = $date->hour;
                    $data['minutes'] = $date->minute;
                }
                break;
        }

        return $data;
    }
}
