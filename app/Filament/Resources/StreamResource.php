<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StreamResource\Pages;
use App\Filament\Resources\StreamResource\RelationManagers;
use App\Models\Stream;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Stream\Models\Enums\StreamStatus;

class StreamResource extends Resource
{
    protected static ?string $model = Stream::class;
    protected static ?string $navigationGroup = 'Education';
    protected static ?string $navigationLabel = 'Streams';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

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

                    return \Modules\User\Models\User::role('teacher')
                        ->whereHas('streams', fn ($query) => $query->where('language_level_id', $levelId))
                        ->pluck('name', 'id');
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
                ->options(collect(StreamStatus::cases())->mapWithKeys(fn($status) => [
                    $status->value => $status->label()
                ]))
                ->default(StreamStatus::PLANNED)
                ->required(),

            Forms\Components\Select::make('current_subject_id')
                ->label('Current Subject')
                ->options(function (Forms\Get $get) {
                    $levelId = $get('language_level_id');
                    if (! $levelId) {
                        return [];
                    }
                    return \Modules\Subject\Models\Subject::where('language_level_id', $levelId)->pluck('title', 'id');
                })
                ->searchable()
                ->required()
                ->reactive(),

            Forms\Components\Toggle::make('repeat')
                ->label('Repeat')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('languageLevel.title')->label('Level')->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Teacher')->sortable(),
                Tables\Columns\TextColumn::make('currentSubject.title')->label('Current Subject')->sortable(),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors([
                        'primary' => StreamStatus::PLANNED,
                        'success' => StreamStatus::STARTED,
                        'warning' => StreamStatus::PAUSED,
                        'danger' => StreamStatus::FINISHED,
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListStreams::route('/'),
            'create' => Pages\CreateStream::route('/create'),
            'edit'   => Pages\EditStream::route('/{record}/edit'),
        ];
    }
}
