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
                                    ])
                                    ->toArray()
                            )
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $set('end', null); // сбросить end при смене start
                                }
                            })
                            ->columnSpan(6),

                        Select::make('end')
                            ->label('End Time')
                            ->required()
                            ->options(fn (callable $get) =>
                            collect(range(6 * 60, 22 * 60, 30))
                                ->mapWithKeys(fn ($minutes) => [
                                    sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60)
                                    => sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60)
                                ])
                                ->filter(fn ($time) => $time > $get('start'))
                                ->toArray()
                            )
                            ->columnSpan(6),
                    ])
                    ->columns(12)
                    ->reorderable(),
            ])
            ->collapsible()->collapsed();
    }
}
