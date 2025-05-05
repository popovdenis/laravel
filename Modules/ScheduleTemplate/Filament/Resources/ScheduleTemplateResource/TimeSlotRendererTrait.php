<?php

namespace Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

/**
 * Trait TimeSlotRendererTrait
 *
 * @package App\Filament\Resources
 */
trait TimeSlotRendererTrait
{
    protected static function makeDaySlotSection(string $dayKey, string $dayLabel, string $field): Section
    {
        return Section::make($dayLabel)
            ->schema([
                Repeater::make("{$dayKey}_{$field}")
                    ->label(false)
                    ->schema([
                        Select::make('start')
                            ->label('Start Time')
                            ->options(
                                collect(range(6 * 60, 22 * 60, 30))
                                    ->mapWithKeys(fn ($minutes) => [
                                        sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60)
                                        => sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60)
                                    ])->toArray()
                            )
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $end = \Carbon\Carbon::createFromFormat('H:i', $state)->addMinutes(60)->format('H:i');
                                    $set('end', $end);
                                } else {
                                    $set('end', null);
                                }
                            })
                            ->required()
                            ->columnSpan(6),

                        TextInput::make('end')
                            ->label('End Time')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpan(6),
                    ])
                    ->columns(12)
                    ->reorderable(),
            ])
            ->collapsible()->collapsed();
    }
}
