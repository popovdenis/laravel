<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageLevelResource\Pages;
use App\Filament\Resources\LanguageLevelResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\LanguageLevel\Models\LanguageLevel;

class LanguageLevelResource extends Resource
{
    protected static ?string $model = LanguageLevel::class;
    protected static ?string $navigationGroup = 'School';
    protected static ?string $navigationLabel = 'Levels';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([Forms\Components\Grid::make(12)->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                })->columnSpan(6),
            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->lazy()
                ->dehydrated()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                })->columnSpan(6),
            Textarea::make('description')->required()->columnSpan(12),
            Toggle::make('is_active')->label('Active')->columnSpan(2),
        ])]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->sortable()->searchable(),
            ToggleColumn::make('is_active'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubjectRelationManager::class,
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
}
