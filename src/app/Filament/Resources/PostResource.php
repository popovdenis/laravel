<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = \App\Blog\Models\Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('postTranslation.title')
                    ->label('Title')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('postTranslation.slug', \Str::slug($state))
                    ),

                TextInput::make('postTranslation.subtitle')
                    ->label('Subtitle'),

                TextInput::make('postTranslation.slug')
                    ->label('Slug')
                    ->required(),

                Toggle::make('is_published')
                    ->label('Published'),

                DateTimePicker::make('posted_at')
                    ->label('Posted At')
                    ->default(now()),

                RichEditor::make('postTranslation.post_body')
                    ->label('Post Body'),

                TextInput::make('postTranslation.seo_title')
                    ->label('SEO Title'),

                Textarea::make('postTranslation.meta_desc')
                    ->label('Meta Description'),

                Textarea::make('postTranslation.short_description')
                    ->label('Short Description'),

                TextInput::make('postTranslation.use_view_file')
                    ->label('Custom View File'),

                Select::make('postTranslation.lang_id')
                    ->label('Language')
                    ->options(\App\Blog\Models\Language::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $post = $data;

        $post['currentTranslation'] = $data['currentTranslation'];

        return $post;
    }

    public static function afterSave(Form $form): void
    {
        $record = $form->getRecord();
        $data = $form->getState()['currentTranslation'];

        $record->currentTranslation()->updateOrCreate(
            ['lang_id' => 1],
            $data
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('posted_at')->dateTime()->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
