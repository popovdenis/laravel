<?php

namespace Modules\Order\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\Order;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([Tabs::make('Order View')
            ->tabs([
                Tabs\Tab::make('Information')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Section::make('Order & Account Information')->icon('heroicon-m-check-badge')->columns(3)->schema([
                                Placeholder::make('id')
                                    ->label('Order #')
                                    ->content(fn ($record) => $record->increment_id),
                                Placeholder::make('created_at')
                                    ->label('Order Date')
                                    ->content(fn($record) => $record->created_at->format('M d, Y H:i')),
                                Placeholder::make('order_status')
                                    ->label('Order Status')
                                    ->content(fn($record) => $record->status ? ucfirst($record->status->value) : '—'),
                            ])->compact()->collapsible(),

                            Section::make('Items Ordered')->icon('heroicon-m-shopping-bag')->schema([
                                Placeholder::make('plan')
                                    ->label('Plan')
                                    ->content(fn($record) => $record->purchasable->plan->name ?? __('Unknown Plan')),
                                Placeholder::make('stripe_id')
                                    ->label('Stripe ID')
                                    ->content(fn($record) => $record->purchasable->stripe_id ?? '—'),
                                Placeholder::make('stripe_price')
                                    ->label('Stripe Price')
                                    ->content(function ($record) {
                                        if (!$record->purchasable->stripe_price) {
                                            return '—';
                                        }
                                        return self::getFormattedPrice((float)$record->purchasable->stripe_price);
                                    }),
                                Placeholder::make('type')
                                    ->label('Subscription Type')
                                    ->content(fn($record) => $record->purchasable->type ?? '—'),
                                Placeholder::make('credits_amount')
                                    ->label('Credits')
                                    ->content(function ($record) {
                                        if (!$record->purchasable->credits_amount) {
                                            return '—';
                                        }
                                        return self::getFormattedPrice((float)$record->purchasable->credits_amount);
                                    }),
                                Placeholder::make('trial_ends_at')
                                    ->label('Trial End At')
                                    ->content(fn($record) => $record->purchasable->trial_ends_at?->format('M d, Y H:i') ?? '-'),
                                Placeholder::make('starts_at')
                                    ->label('Starts At')
                                    ->content(fn($record) => $record->purchasable->starts_at?->format('M d, Y H:i') ?? '-'),
                                Placeholder::make('ends_at')
                                    ->label('Ends At')
                                    ->content(fn($record) => $record->purchasable->ends_at?->format('M d, Y H:i') ?? '-'),
                                Placeholder::make('canceled_at')
                                    ->label('Canceled At')
                                    ->content(fn($record) => $record->purchasable->canceled_at?->format('M d, Y H:i') ?? '-'),
                            ])->collapsible(),

                            Section::make('Account Information')->icon('heroicon-m-identification')->columns(2)->schema([
                                Placeholder::make('user.name')
                                    ->label('Customer Name')
                                    ->content(fn($record) => $record->user?->name ?? '—'),
                                Placeholder::make('user.email')
                                    ->label('Email')
                                    ->content(fn($record) => $record->user?->email ?? '—'),
                            ])->collapsible(),

                            Section::make('Order Totals')->schema([
                                Placeholder::make('total_amount')
                                    ->label('Total Paid')
                                    ->content(function ($record) {
                                        return $record?->total_amount;
                                    })
                                /*->content(function ($record) {
                                    if (!$record?->total_amount || !$record?->currency) {
                                        return '—';
                                    }
                                    return self::getFormattedPrice($record->total_amount, strtoupper($record->total_amount));
                                })*/,
                            ])->compact()->collapsible(),
                        ]),
                    ]),
                Tabs\Tab::make('Invoices')
                    ->schema([
                        ViewField::make('order_link')
                            ->label('Invoice #')
                            ->view('invoice::components.filament.invoice-html-link')->columnSpanFull(),
                    ]),
                Tabs\Tab::make('Transactions')
                    ->schema([
                        // ...
                    ]),
            ])
            ->activeTab(1)->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('increment_id')
                    ->label('Order ID')
                    ->sortable(),

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
            'index' => OrderResource\Pages\ListOrders::route('/'),
            'create' => OrderResource\Pages\CreateOrder::route('/create'),
            'edit' => OrderResource\Pages\EditOrder::route('/{record}/edit'),
            'view' => OrderResource\Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('purchasable_type', \Modules\Subscription\Models\Subscription::class);
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

    protected static function getFormattedPrice($amount, $currency = 'aud'): bool|string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, strtoupper($currency));
    }
}
