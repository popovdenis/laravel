<?php

namespace App\Filament\Resources;

use App\Blog\Models\UploadedPhoto;
use App\Filament\Resources\UploadImageResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;

class UploadImageResource extends Resource
{
    protected static ?string $model = UploadedPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Uploaded Images';
    protected static ?string $navigationGroup = 'Blog';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                TextInput::make('image_title')
                    ->required()
                    ->maxLength(255),
                Hidden::make('uploader_id')
                    ->default(auth()->id()),
                FileUpload::make('uploaded_images')
                    ->required()
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->directory('blog_images')
                    ->preserveFilenames()
                    ->reorderable()
                    ->maxFiles(5)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('uploaded_images.0')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('image_title'),
                Tables\Columns\TextColumn::make('source'),
                Tables\Columns\TextColumn::make('uploader_id'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUploadImages::route('/'),
            'create' => Pages\CreateUploadImage::route('/upload'),
        ];
    }
}
