<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

/**
 * Trait TimeSlotConverter
 *
 * @package App\Filament\Resources\ScheduleTemplateResource\Pages
 */
trait TimeSlotConverter
{
    protected function convertTimeSlotsBeforeSave(array $data): array
    {
        $data['slots'] = collect([
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ])->flatMap(function ($day) use ($data) {
            return collect($data["{$day}_slots"] ?? [])->map(function ($slot) use ($day) {
                return [
                    'day'   => $day,
                    'start' => $slot['start'],
                    'end'   => $slot['end'],
                ];
            });
        })->values()->all();

        return $data;
    }

    protected function convertTimeSlotsBeforeFill(array $data): array
    {
        $grouped = collect($data['slots'] ?? [])
            ->groupBy('day')
            ->mapWithKeys(fn ($slots, $day) => ["{$day}_slots" => $slots->values()->all()]);

        return array_merge($data, $grouped->toArray());
    }
}
