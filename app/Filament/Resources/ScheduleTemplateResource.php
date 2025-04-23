<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleTemplateResource\Pages;
use App\Filament\Resources\ScheduleTemplateResource\RelationManagers;
use App\Models\ScheduleTemplate;
use Filament\Forms;
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
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Template Title')
                    ->required()
                    ->maxLength(255),

                static::makeDaySlotSection('monday', 'Monday'),
                static::makeDaySlotSection('tuesday', 'Tuesday'),
                static::makeDaySlotSection('wednesday', 'Wednesday'),
                static::makeDaySlotSection('thursday', 'Thursday'),
                static::makeDaySlotSection('friday', 'Friday'),
                static::makeDaySlotSection('saturday', 'Saturday'),
                static::makeDaySlotSection('sunday', 'Sunday'),
            ]);
    }

    protected static function makeDaySlotSection(string $dayKey, string $dayLabel): \Filament\Forms\Components\Section
    {
        return \Filament\Forms\Components\Section::make($dayLabel)
            ->schema([
                \Filament\Forms\Components\Repeater::make("{$dayKey}_slots")
                    ->label(false)
                    ->schema([
                        \Filament\Forms\Components\Select::make('start')
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

                        \Filament\Forms\Components\TextInput::make('end')
                            ->label('End Time')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpan(6),
                    ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
