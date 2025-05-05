<?php

namespace Modules\Booking\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Enums\BookingAction;
use Modules\Booking\Models\BookingCreditHistory;

class BookingCreditHistoryResource extends Resource
{
    protected static ?string $model = BookingCreditHistory::class;
    protected static ?string $navigationGroup = 'Booking';
    protected static ?string $navigationLabel = 'Transactions History';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.stream.languageLevel.title')
                    ->label('Stream')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('booking.stream.teacher.email')
                    ->label('Teacher')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('booking.student.email')
                    ->label('Student')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('booking_type')
                    ->label('Booking Type')->sortable()->default('group'),
                Tables\Columns\TextColumn::make('credits_amount')
                    ->label('Billing Amount')->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->label('Action')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => BookingAction::from($state)->color())
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Billing Date and Time')
                    ->dateTime('M d, Y, H:i:s')
                    ->sortable()
                    ->toggleable(),
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
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
            'index' => BookingCreditHistoryResource\Pages\ListBookingCreditHistories::route('/'),
        ];
    }
}
