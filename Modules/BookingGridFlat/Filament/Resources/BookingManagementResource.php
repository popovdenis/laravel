<?php

namespace Modules\BookingGridFlat\Filament\Resources;

use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Enums\BookingStatus;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\CreateBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\EditBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages\ListBookingManagement;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\BookingGridFlat\Models\BookingGridFlat;
use Illuminate\Database\Eloquent\Builder;

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
                Tables\Columns\TextColumn::make('year')
                    ->label('Day')
                    ->getStateUsing(fn ($record) => \Carbon\Carbon::parse($record->start_time)->format('d/m')),
                Tables\Columns\TextColumn::make('time_range')
                    ->label('Time')
                    ->getStateUsing(fn ($record) =>
                        \Carbon\Carbon::parse($record->start_time)->format('H:i') . ' — ' .
                        \Carbon\Carbon::parse($record->end_time)->format('H:i')
                    ),
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

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Booking Status')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => BookingStatus::from($state)->color())
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('start_date')->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Started At')
                        ->native(false)
                        ->minDate(now()),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Ended At')
                        ->native(false)
                        ->minDate(now()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['start_date'], fn ($q) => $q->whereDate('start_time', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->whereDate('start_time', '<=', $data['end_date']));
                    }),
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Teacher')
                    ->options(
                        BookingGridFlat::select('teacher_id', DB::raw('MIN(teacher_fullname) as teacher_fullname'))
                            ->groupBy('teacher_id')
                            ->orderBy('teacher_fullname')
                            ->pluck('teacher_fullname', 'teacher_id')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Student')
                    ->options(
                        BookingGridFlat::select('student_id', DB::raw('MIN(student_fullname) as student_fullname'))
                            ->groupBy('student_id')
                            ->orderBy('student_fullname')
                            ->pluck('student_fullname', 'student_id')
                            ->toArray()
                    ),

                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Student')
                    ->options(
                        BookingGridFlat::select('student_id', DB::raw('MIN(student_fullname) as student_fullname'))
                            ->groupBy('student_id')
                            ->orderBy('student_fullname')
                            ->pluck('student_fullname', 'student_id')
                            ->toArray()
                    ),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_time')
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(6);
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
