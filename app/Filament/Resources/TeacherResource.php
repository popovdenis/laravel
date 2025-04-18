<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\User;

class TeacherResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Members';
    protected static ?string $breadcrumb = 'Members';
    protected static ?string $navigationLabel = 'Teachers';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return UserResource::form($form)->schema(array_merge(
            UserResource::form($form)->getComponents(),
            [
                Forms\Components\Section::make('Schedule Timesheet')
                    ->schema([
                        Forms\Components\Select::make('schedule_template_id')
                            ->label('Select Template')
                            ->options(fn () => \App\Models\ScheduleTemplate::pluck('title', 'id'))
                            ->reactive(),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('load_template')
                                ->label('Load Time Slots')
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                    $templateId = $get('schedule_template_id');
                                    if ($templateId) {
                                        $template = \App\Models\ScheduleTemplate::find($templateId);
                                        if ($template) {
                                            $set('timesheet', $template->slots);
                                        }
                                    }
                                }),
                        ]),

                        Forms\Components\Repeater::make('timesheet')
                            ->label('Time Slots')
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->label('Day')
                                    ->options([
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ])
                                    ->required(),

                                Forms\Components\TimePicker::make('start')->required()->seconds(false),
                                Forms\Components\TimePicker::make('end')->required()->seconds(false),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record?->hasRole('Teacher')),
            ]
        ));
    }

    public static function table(Table $table): Table
    {
        return UserResource::table($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->role('teacher');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
