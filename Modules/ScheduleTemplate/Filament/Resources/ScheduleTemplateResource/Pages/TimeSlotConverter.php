<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

use Carbon\Carbon;

/**
 * Trait TimeSlotConverter
 *
 * @package App\Filament\Resources\ScheduleTemplateResource\Pages
 */
trait TimeSlotConverter
{
    protected function convertTimeSlotsBeforeSave(array $data, $field, string $timezone): array
    {
        $data[$field] = collect([
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ])->each(function ($day) use (&$data, $field, $timezone) {
            $data["{$day}_{$field}"] = collect($data["{$day}_{$field}"] ?? [])->map(function ($slot) use ($day, $timezone) {
                $startUtc = Carbon::createFromFormat('H:i', $slot['start'], $timezone)
                    ->setTimezone('UTC')
                    ->format('H:i');
                $endUtc = Carbon::createFromFormat('H:i', $slot['end'], $timezone)
                    ->setTimezone('UTC')
                    ->format('H:i');

                return [
//                    'day'   => $day,
                    'start' => $startUtc,
                    'end'   => $endUtc,
                ];
            })
            ->all();
        });

        return $data;
    }

    protected function convertTimeSlotsBeforeFill(array $data, $field): array
    {
        $grouped = collect($data[$field] ?? [])
            ->groupBy('day')
            ->mapWithKeys(fn ($slots, $day) => ["{$day}_{$field}" => $slots->values()->all()]);

        return array_merge($data, $grouped->toArray());
    }
}
