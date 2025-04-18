<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Filament\Tables\Actions\DeleteAction;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Select::make('teacher_id')
                    ->label('Teacher')
                    ->relationship(
                        name: 'teacher',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('Teacher')
                    )
                    ->required()
                    ->searchable()
                    ->columnSpan(6),

                Select::make('student_ids')
                    ->label('Students')
                    ->relationship(
                        name: 'students',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('Student')
                    )
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->columnSpan(6),

                Forms\Components\Toggle::make('reschedule')
                    ->label('Re-schedule the meeting')
                    ->inline(false)
                    ->default(false)
                    ->columnSpan(12),

                Select::make('duration')
                    ->label('Time Slot')
                    ->options([
                        '30' => '30 minutes',
                        '45' => '45 minutes',
                        '60' => '60 minutes',
                    ])
                    ->dehydrated(false)
                    ->reactive()
                    ->afterStateHydrated(function (callable $set, $state) {
                        if (blank($state)) {
                            $set('duration', '60');
                        }
                    })
                    ->afterStateUpdated(function (Set $set, $state, callable $get) {
                        if ($start = $get('start_time')) {
                            $set('end_time', \Carbon\Carbon::parse($start)->addMinutes((int) $state)->toDateTimeString());
                        }
                    })
                    ->columnSpan(4),

                DateTimePicker::make('start_time')
                    ->seconds(false)
                    ->native(false)
                    ->label('Start Time')
                    ->required()
                    ->reactive()
                    ->default(Carbon::now())
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $set('end_time', Carbon::parse($state)->addHour()->toDateTimeString());
                        }
                    })
                    ->columnSpan(4),

                DateTimePicker::make('end_time')
                    ->seconds(false)
                    ->native(false)
                    ->label('End Time')
                    ->required()
                    ->reactive()
                    ->default(Carbon::now()->addHour())
                    ->columnSpan(4),

                Forms\Components\TextInput::make('custom_link')
                    ->label('Custom Link')
                    ->url()
                    ->columnSpan(12)
                    ->visible(fn () => ! config('services.zoom.mode') || config('services.zoom.mode') === 'free'),

                Forms\Components\TextInput::make('zoom_meeting_id')
                    ->label('Meeting ID')
                    ->nullable()
                    ->columnSpan(4),

                Forms\Components\TextInput::make('passcode')
                    ->label('Passcode')
                    ->nullable()
                    ->columnSpan(4),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('students_count')
                    ->label('Students')
                    ->counts('students'),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('zoom_meeting_id')
                    ->label('Zoom ID')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_time', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (\Illuminate\Support\Collection $records) {
                        foreach ($records as $record) {
                            if ($record->zoom_meeting_id) {
                                app(\App\Services\ZoomService::class)->delete($record->zoom_meeting_id);
                            }
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
