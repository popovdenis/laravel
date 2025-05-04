<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CronScheduleResource\Pages;
use App\Filament\Resources\CronScheduleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\CronSchedule\Models\CronSchedule;
use Modules\Invoice\Models\Invoice;
use Filament\Forms\Components\{Select, Toggle};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables;
use Carbon\Carbon;
use Modules\CronSchedule\Services\CronCommandRegistryService;

class CronScheduleResource extends Resource
{
    protected static ?string $model = CronSchedule::class;
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Cron Scheduler';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Toggle::make('enabled')
                    ->label('Enable automatic execution')
                    ->reactive()
                    ->columnSpan(8),

                Select::make('target_type')
                    ->label('Schedule applies to')
                    ->options([
                        Invoice::class => 'Invoice',
//                        \App\Models\Meeting::class => 'Meeting',
                    ])
                    ->required()
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->visible(fn ($get) => $get('enabled') === true)
                    ->columnSpan(8),

                Select::make('command')
                    ->label('Command to run')
                    ->options(app(CronCommandRegistryService::class)->optionsForSelect())
                    ->searchable()
                    ->required()
                    ->columnSpan(8)
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->helperText('Select the Artisan command that will be executed by this schedule')
                    ->columnSpan(8),

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
                    ->visible(fn ($get) => $get('enabled') === true)
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
                    ->label('Set a time')
                    ->seconds(false)
                    ->visible(fn (\Filament\Forms\Get $get) => in_array($get('frequency'), ['daily', 'weekly', 'monthly']))
                    ->extraAttributes(['style' => 'width: 400px'])
                    ->afterStateHydrated(function (\Filament\Forms\Set $set, $record) {
                        if ($record && $record->hours !== null && $record->minutes !== null) {
                            $set('schedule_time', Carbon::createFromTime($record->hours, $record->minutes)->format('H:i'));
                        }
                    })
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

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                IconColumn::make('enabled')
                    ->label('Enabled')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('target_type')
                    ->label('Applies to')
                    ->formatStateUsing(fn (string $state) => class_basename($state))
                    ->sortable(),

                TextColumn::make('frequency')
                    ->label('Frequency')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->sortable(),

                TextColumn::make('day')
                    ->label('Day')
                    ->formatStateUsing(fn ($state, $record) => $record->frequency === 'monthly' ? ($state ?? '—') : '—')
                    ->toggleable(),

                TextColumn::make('day_of_week')
                    ->label('Weekday')
                    ->formatStateUsing(fn ($state, $record) => match (true) {
                        in_array($record->frequency, ['weekly', 'monthly']) => [
                            0 => 'Sunday',
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                        ][$state] ?? '—',
                        default => '—',
                    })
                    ->toggleable(),

                TextColumn::make('hours')
                    ->label('Hour')
                    ->formatStateUsing(fn ($state, $record) =>
                    in_array($record->frequency, ['daily', 'weekly', 'monthly']) ? str_pad($state, 2, '0', STR_PAD_LEFT) : '—')
                    ->toggleable(),

                TextColumn::make('minutes')
                    ->label('Minute')
                    ->formatStateUsing(fn ($state) => str_pad($state, 2, '0', STR_PAD_LEFT))
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->wrap()
                    ->limit(50)
                    ->toggleable(),
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
