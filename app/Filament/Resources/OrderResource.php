<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('purchasable.plan.name')
                    ->label('Subscription Plan')
                    ->formatStateUsing(function ($record) {
                        return $record->purchasable?->plan?->name ?? '—';
                    })
                    ->sortable(),

                TextColumn::make('purchasable')
                    ->label('Subscription Plan')
                    ->formatStateUsing(fn ($record) => $record->purchasable?->plan?->name ?? '—'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'complete' => 'success',
                        'cancelled' => 'danger',
                    ])
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Amount')
                    ->money('usd')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('purchasable_type', \Modules\Subscription\Models\Subscription::class);
    }
}
