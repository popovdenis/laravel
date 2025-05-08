<?php

namespace Modules\BookingGridFlat\Filament\Resources;

use Modules\Booking\Enums\BookingAction;
use Modules\Booking\Enums\BookingStatus;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\CreateBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\EditBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\ListBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\BookingGridFlat\Models\BookingGridFlat;

class BookingManagementResource extends Resource
{
    protected static ?string $model = BookingGridFlat::class;
    protected static ?string $navigationGroup = 'Booking';
    protected static ?string $navigationLabel = 'Booking Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('teacher_fullname')
                    ->label('Teacher')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('student_fullname')
                    ->label('Student')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('level_title')
                    ->label('Level')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('subject_title')
                    ->label('Subject')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('current_subject_number')
                    ->label('Subject Number')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Subject Lesson')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Lesson')->sortable()->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Booking Status')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => BookingStatus::from($state)->color())
                    ->toggleable()
                    ->sortable(),
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
            'index' => ListBookingManagement::route('/'),
            'create' => CreateBookingManagement::route('/create'),
            'edit' => EditBookingManagement::route('/{record}/edit'),
        ];
    }
}
