<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditScheduleTemplate extends EditRecord
{
    protected static string $resource = ScheduleTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $slots = $this->data["{$day}_slots"] ?? [];

            foreach ($slots as $index => $slot) {
                $startA = \Carbon\Carbon::createFromFormat('H:i', $slot['start']);
                $endA = $startA->copy()->addMinutes(60);

                foreach ($slots as $index2 => $slot2) {
                    if ($index === $index2) {
                        continue;
                    }

                    $startB = \Carbon\Carbon::createFromFormat('H:i', $slot2['start']);
                    $endB = $startB->copy()->addMinutes(60);

                    if (
                        $startA->lessThan($endB) && $endA->greaterThan($startB)
                    ) {
                        Notification::make()
                            ->title('Error')
                            ->body("Time slots overlap on {$day} between {$slot['start']} and {$slot2['start']}.")
                            ->danger()
                            ->send();

                        $this->halt();
                    }
                }
            }
        }
    }
}
