<?php

namespace Modules\Invoice\Filament\Resources;

use Modules\Invoice\Filament\Resources\InvoiceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Invoice\Enums\InvoiceStatusEnum;
use Modules\Invoice\Models\Invoice;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $label = 'Invoices';
    protected static ?string $slug = 'invoices';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Section::make('Invoice Information')->icon('heroicon-m-check-badge')->columns(4)->schema([
                    Placeholder::make('invoice_status')
                        ->label('Invoice Status')
                        ->content(fn($record) => ucfirst($record->status)),
                    Placeholder::make('invoice_date')
                        ->label('Invoice Date')
                        ->content(fn($record) => \Carbon\Carbon::parse($record->invoice_created_at)->format('M d, Y H:i')),
                    Actions::make([
                        Action::make('openHostedLink')
                            ->label('Download Receipt')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url(fn($record) => $record->hosted_url)
                            ->openUrlInNewTab()
                            ->visible(fn($record) => filled($record->hosted_url)),
                    ]),
                    Actions::make([
                        Action::make('openPdfLink')
                            ->label('Download PDF')
                            ->icon('heroicon-o-document-arrow-down')
                            ->url(fn($record) => $record->pdf_url)
                            ->openUrlInNewTab()
                            ->visible(fn($record) => filled($record->pdf_url)),
                    ])
                ])->collapsible(),

                Section::make('Order & Account Information')->icon('heroicon-m-check-badge')->columns(3)->schema([
                    ViewField::make('order_link')
                        ->label('Order #')
                        ->view('order::components.filament.order-html-link')->columnSpanFull(),
                    Placeholder::make('order.created_at')
                        ->label('Order Date')
                        ->content(fn($record) => $record->order?->created_at->format('M d, Y H:i')),
                    Placeholder::make('order_status')
                        ->label('Order Status')
                        ->content(fn($record) => $record->order?->status ? ucfirst($record->order?->status->value) : '—')
                ])->collapsible(),

                Section::make('Items Invoiced')->icon('heroicon-m-shopping-bag')->schema([
                    Placeholder::make('plan')
                        ->label('Plan')
                        ->content(fn($record) => $record->order?->purchasable?->plan->name ?? __('Unknown Plan')),
                    Placeholder::make('credits')
                        ->label('Credits')
                        ->content(fn($record) => $record->order?->purchasable?->plan->credits ?? '—'),
                    Placeholder::make('stripe_id')
                        ->label('Stripe ID')
                        ->content(fn($record) => $record->order?->purchasable?->stripe_id ?? '—'),
                ])->collapsible(),

                Section::make('Account Information')->icon('heroicon-m-identification')->columns(2)->schema([
                    Placeholder::make('customer_name')
                        ->label('Customer Name')
                        ->content(fn($record) => $record->user?->name ?? '—'),

                    Placeholder::make('customer_email')
                        ->label('Email')
                        ->content(fn($record) => $record->user?->email ?? '—'),
                ])->collapsible(),

                Section::make('Invoice Totals')->schema([
                    Placeholder::make('amount_due')
                        ->label('Amount Due')
                        ->content(function ($record) {
                            if (!$record?->amount_due || !$record?->currency) {
                                return '—';
                            }
                            return self::getFormattedPrice($record->amount_due, strtoupper($record->currency));
                        }),
                    Placeholder::make('subtotal')
                        ->label('Subtotal')
                        ->content(function ($record) {
                            if (!$record?->subtotal || !$record?->currency) {
                                return '—';
                            }
                            return self::getFormattedPrice($record->subtotal, strtoupper($record->currency));
                        }),
                    Placeholder::make('tax')
                        ->label('Tax')
                        ->content(function ($record) {
                            if (!$record?->tax || !$record?->currency) {
                                return '—';
                            }
                            return self::getFormattedPrice($record->tax, strtoupper($record->currency));
                        }),
                    Placeholder::make('total_excl_tax')
                        ->label('Grand Total Excl. Tax')
                        ->content(function ($record) {
                            if (!$record?->total_excl_tax || !$record?->currency) {
                                return '—';
                            }
                            return self::getFormattedPrice($record->total_excl_tax, strtoupper($record->currency));
                        }),
                    Placeholder::make('total')
                        ->label('Grand Total')
                        ->content(function ($record) {
                            if (!$record?->total || !$record?->currency) {
                                return '—';
                            }
                            return self::getFormattedPrice($record->total, strtoupper($record->currency));
                        }),
                ])->collapsible(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Invoice')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('invoice_created_at')
                    ->label('Invoice Date')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('M d, Y H:i'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order.id')
                     ->label('Order #')
                     ->formatStateUsing(fn ($state) => $state ? '#' . self::formatWithTemplate($state) : '—')
                     ->url(fn ($record) => $record->order
                         ? route('filament.admin.resources.orders.view', ['record' => $record->order->id])
                         : null)
                     ->openUrlInNewTab()
                     ->color('primary')
                     ->toggleable()
                     ->sortable(),

                TextColumn::make('order.created_at')
                    ->label('Order Date')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('M d, Y H:i'))
                    ->toggleable(),

                TextColumn::make('order.user.name')
                    ->label('Bill-to Name')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => InvoiceStatusEnum::from($state)->color())
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('increment_id')
                    ->label('Increment ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('order.user.name')
                    ->label('Customer')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total')
                    ->label('Grand Total (Base)')
                    ->formatStateUsing(fn($record) => self::getFormattedPrice($record->total, strtoupper($record->currency)))
                    ->sortable()
                    ->toggleable(),


                TextColumn::make('amount_paid')
                    ->label('Grand Total (Purchased)')
                    ->formatStateUsing(fn ($record) => self::getFormattedPrice($record->amount_paid, strtoupper($record->currency)))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('order.user.email')
                    ->label('Customer Email')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['order.user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => InvoiceResource\Pages\ListInvoices::route('/'),
            'view' => InvoiceResource\Pages\ViewInvoice::route('/{record}'),
        ];
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

    private static function formatWithTemplate(int $id, string $template = '00000000'): string
    {
        return substr($template, 0, -strlen((string)$id)) . $id;
    }

    protected static function getFormattedPrice($amount, $currency): bool|string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, strtoupper($currency));
    }
}
