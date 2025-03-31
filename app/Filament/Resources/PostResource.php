<?php

namespace App\Filament\Resources;

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
use Filament\Forms\Components\FileUpload;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                ->schema([
                    Select::make('currentTranslation.lang_id')
                        ->label('Language')
                        ->options(Language::pluck('name', 'id'))
                        ->required()
                        ->columnSpan(4),

                    TextInput::make('currentTranslation.title')
                        ->label('Title')
                        ->required()
                        ->reactive()
                        ->debounce(1000)
                        ->afterStateUpdated(fn($state, callable $set) =>
                            $set('currentTranslation.slug', Str::slug($state))
                        )
                        ->columnSpan(4),

                    TextInput::make('currentTranslation.slug')
                        ->label('Slug')
                        ->required()
                        ->columnSpan(4),

                    TextInput::make('currentTranslation.subtitle')
                        ->label('Subtitle')
                        ->columnSpan(6),

                    DateTimePicker::make('posted_at')
                        ->label('Posted At')
                        ->default(now())
                        ->columnSpan(6),

                    Toggle::make('is_published')
                        ->label('Published')
                        ->columnSpan(2),

                    Textarea::make('currentTranslation.short_description')
                        ->label('Short Description')
                        ->columnSpan(12),

                    RichEditor::make('currentTranslation.post_body')
                        ->label('Post Body')
                        ->columnSpan(12),

                    TextInput::make('currentTranslation.seo_title')
                        ->label('SEO Title')
                        ->columnSpan(4),

                    Textarea::make('currentTranslation.meta_desc')
                        ->label('Meta Description')
                        ->columnSpan(4),

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
                        ->columnSpan(4),

                    FileUpload::make('currentTranslation.image_large')
                        ->label('Image')
                        ->image()
                        ->directory(config('blog.blog_upload_dir', 'blog_images'))
                        ->preserveFilenames()
                        ->disk('public')
                        ->columnSpan(4),
                ])
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
