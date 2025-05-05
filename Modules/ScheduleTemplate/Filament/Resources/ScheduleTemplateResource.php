<?php

namespace Modules\ScheduleTemplate\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\RelationManagers;
use Modules\ScheduleTemplate\Filament\Resources\ScheduleTemplateResource\TimeSlotRendererTrait;
use Modules\ScheduleTemplate\Models\ScheduleTemplate;

class ScheduleTemplateResource extends Resource
{
    use TimeSlotRendererTrait;

    protected static ?string $model = ScheduleTemplate::class;
    protected static ?string $navigationGroup = 'Marketing';
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
                static::makeDaySlotSection('monday', 'Monday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('tuesday', 'Tuesday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('wednesday', 'Wednesday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('thursday', 'Thursday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('friday', 'Friday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('saturday', 'Saturday', 'slots')->columnSpan(6),
                static::makeDaySlotSection('sunday', 'Sunday', 'slots')->columnSpan(6),
            ])
        ]);
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
            'index' => ScheduleTemplateResource\Pages\ListScheduleTemplates::route('/'),
            'create' => ScheduleTemplateResource\Pages\CreateScheduleTemplate::route('/create'),
            'edit' => ScheduleTemplateResource\Pages\EditScheduleTemplate::route('/{record}/edit'),
        ];
    }
}
