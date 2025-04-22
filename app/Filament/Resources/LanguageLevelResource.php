<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageLevelResource\Pages;
use App\Filament\Resources\LanguageLevelResource\RelationManagers;
use App\Models\LanguageLevel;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class LanguageLevelResource extends Resource
{
    protected static ?string $model = LanguageLevel::class;
    protected static ?string $navigationGroup = 'Study';
    protected static ?string $navigationLabel = 'Levels';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                }),
            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->lazy()
                ->dehydrated()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                }),
            Textarea::make('description')->required(),
            TextInput::make('level')->maxLength(100),
            TextInput::make('duration')->maxLength(100),
            TextInput::make('price')
                ->numeric()
                ->prefix('$')
                ->required(),
            Toggle::make('is_active')->label('Active'),
            TextInput::make('sort_order')->numeric(),
            Select::make('teachers')
                ->label('Teachers')
                ->multiple()
                ->searchable()
                ->preload()
                ->options(
                    \App\Models\User::all()
                        ->filter(fn ($user) => $user->hasRole('Teacher'))
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->afterStateHydrated(function ($component) {
                    if (blank($component->getState()) && $component->getRecord()) {
                        $component->state(
                            $component->getRecord()->teachers->pluck('id')->toArray()
                        );
                    }
                })
                ->dehydrateStateUsing(fn ($state) => $state ?? [])
                ->saveRelationshipsUsing(function (\App\Models\LanguageLevel $record, $state) {
                    $record->teachers()->sync($state ?? []);
                })
                ->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->sortable()->searchable(),
            TextColumn::make('level')->sortable(),
            TextColumn::make('duration'),
            TextColumn::make('price')->money('USD'),
            ToggleColumn::make('is_active'),
            TextColumn::make('sort_order')->sortable(),
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
            'index' => Pages\ListLevel::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('teachers');
    }
}
