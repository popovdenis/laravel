<?php

namespace App\Filament\Resources;

use App\Blog\Models\CategoryTranslation;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use App\Blog\Models\Language;
use App\Blog\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('currentTranslation.lang_id')
                    ->label('Language')
                    ->options(Language::pluck('name', 'id'))
                    ->required(),

                TextInput::make('currentTranslation.title')
                    ->label('Title')
                    ->required()
                    ->reactive()
                    ->debounce(1000)
                    ->afterStateUpdated(fn($state, callable $set) =>
                        $set('currentTranslation.slug', Str::slug($state))
                    ),

                TextInput::make('currentTranslation.subtitle')
                    ->label('Subtitle'),

                TextInput::make('currentTranslation.slug')
                    ->label('Slug')
                    ->required(),

                Toggle::make('is_published')
                    ->label('Published'),

                DateTimePicker::make('posted_at')
                    ->label('Posted At')
                    ->default(now()),

                RichEditor::make('currentTranslation.post_body')
                    ->label('Post Body'),

                TextInput::make('currentTranslation.seo_title')
                    ->label('SEO Title'),

                Textarea::make('currentTranslation.meta_desc')
                    ->label('Meta Description'),

                Textarea::make('currentTranslation.short_description')
                    ->label('Short Description'),

                TextInput::make('currentTranslation.use_view_file')
                    ->label('Custom View File'),

                CheckboxList::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'id')
                    ->options(
                        \App\Blog\Models\Category::with(['categoryTranslations' => fn($q) => $q->where('lang_id', 1)])
                            ->get()
                            ->mapWithKeys(function ($category) {
                                $translation = $category->categoryTranslations->first();
                                return $translation ? [$category->id => $translation->category_name] : [];
                            })
                    )
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currentTranslation.title')->label('Title'),
                Tables\Columns\TextColumn::make('currentTranslation.slug')->label('Slug'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_published')
                    ->label('Published')
                    ->options([
                        '1' => 'Published',
                        '0' => 'Unpublished',
                    ]),

                Tables\Filters\Filter::make('title')
                    ->form([
                        Forms\Components\TextInput::make('title')->label('Title contains'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereHas('currentTranslation', function ($q) use ($data) {
                            $q->where('title', 'like', '%' . $data['title'] . '%');
                        });
                    }),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function afterSave(Forms\Form $form): void
    {
        $record = $form->getRecord();
        $data = $form->getState()['currentTranslation'];

        $record->currentTranslation()->updateOrCreate(
            ['lang_id' => $data['lang_id']],
            $data
        );
    }
}
