<?php

namespace App\Filament\Resources;

use App\Blog\Models\UploadedPhoto;
use App\Filament\Resources\UploadImageResource\Pages;
use Filament\Forms;
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
        return $form->schema([
            Forms\Components\FileUpload::make('uploaded_images')
                ->multiple()
                ->required()
                ->directory('blog_images'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('source'),
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
