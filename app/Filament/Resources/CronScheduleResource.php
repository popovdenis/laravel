<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CronScheduleResource\Pages;
use App\Filament\Resources\CronScheduleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\CronSchedule\Models\CronSchedule;
use Filament\Forms\Components\{Checkbox, Select, DatePicker, Grid, Toggle};
use Filament\Tables\Columns\{TextColumn, IconColumn};

class CronScheduleResource extends Resource
{
    protected static ?string $model = CronSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Toggle::make('enabled')->label('Enable automatic execution')->columnSpan(8),

                Select::make('frequency')
                    ->label('Execution frequency')
                    ->options([
                        'hourly' => 'Every hour',
                        'daily' => 'Every day',
                        'weekly' => 'Every week',
                        'monthly' => 'Every month',
                        'once' => 'Once at a specific time',
                    ])
                    ->required()
                    ->reactive()
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->columnSpan(8),

                Select::make('day_of_week')
                    ->label('Day of the week')
                    ->options([
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        0 => 'Sunday',
                    ])
                    ->visible(fn (\Filament\Forms\Get $get) => in_array($get('frequency'), ['weekly', 'monthly']))
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->columnSpan(8),

                // Daily, Weekly, Monthly, Once — Time (hh:mm)
                Forms\Components\TimePicker::make('schedule_time')
                    ->label('Date & Time')
                    ->native(false)
                    ->seconds(false)
                    ->visible(fn (\Filament\Forms\Get $get) => in_array($get('frequency'), ['daily', 'weekly', 'monthly']))
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->columnSpan(8),

                // Once
                Forms\Components\DateTimePicker::make('once_date')
                    ->label('Date')
                    ->native(false)
                    ->seconds(false)
                    ->minDate(now())
                    ->visible(fn (\Filament\Forms\Get $get) => $get('frequency') === 'once')
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->columnSpan(8),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('enabled')
                    ->label('Enabled')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('frequency')
                    ->label('Frequency')
                    ->sortable(),

                TextColumn::make('time')
                    ->label('Time')
                    ->formatStateUsing(function ($record) {
                        if ($record->frequency === 'hourly') {
                            return 'At minute ' . str_pad($record->minute ?? 0, 2, '0', STR_PAD_LEFT);
                        }

                        if (in_array($record->frequency, ['daily', 'weekly', 'monthly', 'once'])) {
                            return str_pad($record->hour ?? 0, 2, '0', STR_PAD_LEFT) . ':' . str_pad($record->minute ?? 0, 2, '0', STR_PAD_LEFT);
                        }

                        return '-';
                    }),

                TextColumn::make('details')
                    ->label('Details')
                    ->formatStateUsing(function ($record) {
                        return match ($record->frequency) {
                            'weekly' => 'On ' . [
                                    0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                                    4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'
                                ][$record->day_of_week ?? 0],
                            'monthly' => 'On day ' . ($record->day_of_month ?? '—'),
                            'once' => $record->once_at?->format('Y-m-d H:i') ?? '—',
                            default => '',
                        };
                    }),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListCronSchedules::route('/'),
            'create' => Pages\CreateCronSchedule::route('/create'),
            'edit' => Pages\EditCronSchedule::route('/{record}/edit'),
        ];
    }
}
