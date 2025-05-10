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
                return [
                    'day'   => $day,
                    'start' => $slot['start'],
                    'end'   => $slot['end'],
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
