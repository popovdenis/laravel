<?php
namespace App\Filament\Resources;

use App\Blog\Models\Comment;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Comments';
    protected static ?string $navigationGroup = 'Blog';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author_name')->label('Author'),
                Tables\Columns\TextColumn::make('comment')->limit(80)->label('Comment'),
                Tables\Columns\TextColumn::make('post.title')->label('Post'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y H:i'),
                Tables\Columns\IconColumn::make('approved')->boolean()->label('Approved'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->visible(fn(Comment $record) => !$record->approved)
                    ->label('Approve')
                    ->action(fn(Comment $record) => $record->update(['approved' => true]))
                    ->requiresConfirmation()
                    ->color('success'),

                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
        ];
    }
}
