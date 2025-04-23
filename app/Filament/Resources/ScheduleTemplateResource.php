<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleTemplateResource\Pages;
use App\Filament\Resources\ScheduleTemplateResource\RelationManagers;
use App\Models\ScheduleTemplate;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleTemplateResource extends Resource
{
    protected static ?string $model = ScheduleTemplate::class;
    protected static ?string $navigationGroup = 'Education';
    protected static ?string $navigationLabel = 'Schedule Templates';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([Forms\Components\Grid::make(12)->schema([
                TextInput::make('title')
                    ->label('Template Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(12),
                static::makeDaySlotSection('monday', 'Monday')->columnSpan(6),
                static::makeDaySlotSection('tuesday', 'Tuesday')->columnSpan(6),
                static::makeDaySlotSection('wednesday', 'Wednesday')->columnSpan(6),
                static::makeDaySlotSection('thursday', 'Thursday')->columnSpan(6),
                static::makeDaySlotSection('friday', 'Friday')->columnSpan(6),
                static::makeDaySlotSection('saturday', 'Saturday')->columnSpan(6),
                static::makeDaySlotSection('sunday', 'Sunday')->columnSpan(6),
            ])
        ]);
    }

    protected static function makeDaySlotSection(string $dayKey, string $dayLabel): Section
    {
        return Section::make($dayLabel)
            ->schema([
                Repeater::make("{$dayKey}_slots")
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
                    ->reorderable()
                    ->default([]),
            ])
            ->collapsible();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduleTemplates::route('/'),
            'create' => Pages\CreateScheduleTemplate::route('/create'),
            'edit' => Pages\EditScheduleTemplate::route('/{record}/edit'),
        ];
    }
}
