<?php
declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Purchased Courses';
    protected static ?string $label = 'Course';
    protected static ?string $pluralLabel = 'Courses';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('itemable.title')->label('Course'),
                TextColumn::make('quantity'),
                TextColumn::make('itemable.price')->label('Price')->money('USD'),
            ])
            ->defaultSort('id');
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canDelete($record): bool
    {
        return false;
    }

    protected function canEdit($record): bool
    {
        return false;
    }
}
