<?php

namespace App\Filament\Resources;

use App\Blog\Models\Category;
use App\Filament\Resources\CategoryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('categoryTranslations.0.category_name')
                ->label('Category Name')
                ->required(),

            Forms\Components\Textarea::make('categoryTranslations.0.category_description')
                ->label('Description'),

            Forms\Components\Select::make('parent_id')
                ->label('Parent Category')
                ->relationship('parent', 'categoryTranslations.0.category_name')
                ->searchable()
                ->preload()
                ->default(0)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoryTranslations.0.category_name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('slug'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
