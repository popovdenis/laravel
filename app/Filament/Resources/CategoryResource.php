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
        return $form
            ->schema([
                Forms\Components\TextInput::make('categoryTranslation.category_name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('categoryTranslation.slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Letters, numbers, dash only. Must be unique.'),

//                Forms\Components\Select::make('parent_id')
//                    ->label('Parent Category')
//                    ->relationship('parent', 'categoryTranslation.category_name')
//                    ->default(0),

                Forms\Components\Textarea::make('categoryTranslation.category_description')
                    ->label('Description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoryTranslations.category_name')->label('Name'),
                Tables\Columns\TextColumn::make('categoryTranslations.slug')->label('Slug'),
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
