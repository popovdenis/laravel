<?php

namespace Modules\User\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotRendererTrait;
use Modules\User\Filament\Resources\TeacherResource\RelationManagers;
use Modules\User\Models\User;

class TeacherResource extends Resource
{
    use TimeSlotRendererTrait;

    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Members';
    protected static ?string $breadcrumb = 'Members';
    protected static ?string $navigationLabel = 'Teachers';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return UserResource::form($form)->schema(array_merge(
            UserResource::form($form)->getComponents(),
            self::getTeacherForm()
        ));
    }

    protected static function getTeacherForm()
    {
        return [
            Forms\Components\Section::make('Schedule Timesheet')
                ->schema([
                    Forms\Components\Grid::make(12)->schema([
                        static::makeDaySlotSection('monday', 'Monday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('tuesday', 'Tuesday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('wednesday', 'Wednesday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('thursday', 'Thursday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('friday', 'Friday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('saturday', 'Saturday', 'timesheet')->columnSpan(12),
                        static::makeDaySlotSection('sunday', 'Sunday', 'timesheet')->columnSpan(12),
                    ])
                ])
                ->visible(fn ($record) => $record?->hasRole('Teacher'))
        ];
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
            'index' => TeacherResource\Pages\ListTeachers::route('/'),
            'create' => TeacherResource\Pages\CreateTeacher::route('/create'),
            'edit' => TeacherResource\Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
