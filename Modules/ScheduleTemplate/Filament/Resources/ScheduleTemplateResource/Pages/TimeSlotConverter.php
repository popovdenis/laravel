<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\Pages;

/**
 * Trait TimeSlotConverter
 *
 * @package App\Filament\Resources\ScheduleTemplateResource\Pages
 */
trait TimeSlotConverter
{
    protected function convertTimeSlotsBeforeSave(array $data, $field): array
    {
        $data[$field] = collect([
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ])->flatMap(function ($day) use ($data, $field) {
            return collect($data["{$day}_{$field}"] ?? [])->map(function ($slot) use ($day) {
                return [
                    'day'   => $day,
                    'start' => $slot['start'],
                    'end'   => $slot['end'],
                ];
            });
        })->values()->all();

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
