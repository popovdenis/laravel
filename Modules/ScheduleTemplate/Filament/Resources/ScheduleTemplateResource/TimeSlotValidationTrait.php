<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;

use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Modules\Base\Conracts\TimezoneInterface;

/**
 * Trait TimeSlotValidationTrait
 *
 * @package App\Filament\Resources\ScheduleTemplateResource\Pages
 */
trait TimeSlotValidationTrait
{
    protected function validateOverlappingSlots(array $data, $field): void
    {
        $timezone = app()->make(TimezoneInterface::class);
        $userTimezone = $this->record->timeZoneId ?? $timezone->getConfigTimezone();

        collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->each(function ($day) use ($data, $field, $userTimezone) {
                $slots = collect($data["{$day}_{$field}"] ?? []);
                $this->checkOverlapsForDay($day, $slots, $userTimezone);
            });
    }

    protected function checkOverlapsForDay(string $day, \Illuminate\Support\Collection $slots, string $timezone): void
    {
        $label = ucfirst($day);

        $slots->each(function ($slotA, $i) use ($slots, $label, $timezone) {
            $timezone = str_replace('\/', '/', $timezone);
            $startA = Carbon::createFromFormat('H:i', $slotA['start_time'], $timezone)->setTimezone('UTC');
            $endA = Carbon::createFromFormat('H:i', $slotA['end_time'], $timezone)->setTimezone('UTC');

            $slots->each(function ($slotB, $j) use ($i, $startA, $endA, $label, $slotA, $timezone) {
                if ($i === $j) {
                    return;
                }

                $startB = Carbon::createFromFormat('H:i', $slotB['start_time'], $timezone)->setTimezone('UTC');
                $endB = Carbon::createFromFormat('H:i', $slotB['end_time'], $timezone)->setTimezone('UTC');

                if ($startA->lt($endB) && $endA->gt($startB)) {
                    Notification::make()
                        ->title('Error')
                        ->body("Time slots overlap on {$label} between {$slotA['start_time']} and {$slotB['start_time']}.")
                        ->danger()
                        ->send();

                    try {
                        $this->halt();
                    } catch (Halt $e) {
                    }
                }
            });
        });
    }
}
