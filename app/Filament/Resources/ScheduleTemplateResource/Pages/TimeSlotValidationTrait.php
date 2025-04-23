<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use Filament\Notifications\Notification;

/**
 * Trait TimeSlotValidationTrait
 *
 * @package App\Filament\Resources\ScheduleTemplateResource\Pages
 */
trait TimeSlotValidationTrait
{
    protected function validateOverlappingSlots(array $data): void
    {
        collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->each(function ($day) use ($data) {
                $slots = collect($data["{$day}_slots"] ?? []);
                $this->checkOverlapsForDay($day, $slots);
            });
    }

    protected function checkOverlapsForDay(string $day, \Illuminate\Support\Collection $slots): void
    {
        $label = ucfirst($day);

        $slots->each(function ($slotA, $i) use ($slots, $label) {
            $startA = \Carbon\Carbon::createFromFormat('H:i', $slotA['start']);
            $endA = $startA->copy()->addMinutes(60);

            $slots->each(function ($slotB, $j) use ($i, $startA, $endA, $label, $slotA) {
                if ($i === $j) {
                    return; // Skip self
                }

                $startB = \Carbon\Carbon::createFromFormat('H:i', $slotB['start']);
                $endB = $startB->copy()->addMinutes(60);

                if ($startA->lessThan($endB) && $endA->greaterThan($startB)) {
                    Notification::make()
                        ->title('Error')
                        ->body("Time slots overlap on {$label} between {$slotA['start']} and {$slotB['start']}.")
                        ->danger()
                        ->send();

                    $this->halt();
                }
            });
        });
    }
}
