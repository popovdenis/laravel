<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreamResource\Pages;
use App\Filament\Resources\StreamResource\RelationManagers;
use App\Models\Stream;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StreamResource extends Resource
{
    protected static ?string $model = Stream::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Education';
    protected static ?string $navigationLabel = 'Streams';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('language_level_id')
                ->label('Language Level')
                ->relationship('languageLevel', 'title')
                ->required()
                ->reactive(),

            Forms\Components\Select::make('teacher_id')
                ->label('Teacher')
                ->options(function (Forms\Get $get) {
                    $levelId = $get('language_level_id');
                    if (!$levelId) {
                        return [];
                    }
                    return \App\Models\User::whereHas('languageLevels', function ($query) use ($levelId) {
                        $query->where('language_level_id', $levelId);
                    })->role('teacher')->pluck('name', 'id');
                })
                ->required(),

            Forms\Components\DatePicker::make('start_date')
                ->label('Start Time')
                ->native(false)
                ->required()
                ->default(Carbon::now())
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->label('End Time')
                ->native(false)
                ->default(Carbon::now()),

            Forms\Components\Select::make('status')
                ->options([
                    'planned' => 'Planned',
                    'started' => 'Started',
                    'paused' => 'Paused',
                    'finished' => 'Finished',
                ])
                ->default('planned')
                ->required(),

            Forms\Components\TextInput::make('current_subject_number')
                ->numeric()
                ->default(1)
                ->minValue(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('languageLevel.title')->label('Level')->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->sortable(),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors([
                        'primary' => 'planned',
                        'success' => 'started',
                        'warning' => 'paused',
                        'danger' => 'finished',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListStreams::route('/'),
            'create' => Pages\CreateStream::route('/create'),
            'edit'   => Pages\EditStream::route('/{record}/edit'),
        ];
    }
}
