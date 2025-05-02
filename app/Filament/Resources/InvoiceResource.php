<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Modules\Invoice\Enums\InvoiceStatusEnum;
use Modules\Invoice\Models\Invoice;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\ViewField;
use Laravel\Cashier\Cashier;
use NumberFormatter;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $label = 'Invoices';
    protected static ?string $slug = 'invoices';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 4;

    protected static function getFormattedPrice($amount, $currency)
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, strtoupper($currency));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Section::make('Invoice Information')->schema([
                            Placeholder::make('invoice_status')
                                       ->label('Invoice Status')
                                       ->content(fn ($record) => $record->status),
                            Placeholder::make('invoice_date')
                                       ->label('Invoice Date')
                                        ->content(fn ($record) => \Carbon\Carbon::parse($record->invoice_created_at)->format('M d, Y H:i')),
                        ])->collapsible(),

                        Section::make('Order & Account Information')->schema([
                            ViewField::make('order_link')
                                     ->label('Order')
                                     ->view('components.filament.html-link')->columnSpanFull(),
                            Placeholder::make('order.created_at')
                                       ->label('Order Date')
                                       ->content(fn ($record) => $record->created_at->format('M d, Y H:i')),
                            Placeholder::make('order.status')
                                       ->label('Order Status')
                                       ->content(fn ($record) => ucfirst($record->status)),
                       ])->collapsible(),

                        Section::make('Account Information')->schema([
                            Placeholder::make('customer_name')
                                       ->label('Customer Name')
                                       ->content(fn ($record) => $record->user?->name ?? '—'),

                            Placeholder::make('customer_email')
                                       ->label('Email')
                                       ->content(fn ($record) => $record->user?->email ?? '—'),
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

    public static function form1(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Invoice Details')->schema([
                    Forms\Components\Grid::make(6)->schema([

                        Placeholder::make('order.user.name')
                            ->label('Customer')
                            ->content(fn ($record) => $record->order->user->getFullNameAttribute())->columnSpan(6),
                    ])->columnSpan(1),
                    Forms\Components\Grid::make(6)->schema([
                        Placeholder::make('amount_due')
                                   ->label('Amount Due')
                                   ->content(fn ($record) => self::getFormattedPrice($record->amount_due, strtoupper($record->currency)) ?? __('None'))->columnSpan(6),
                        Placeholder::make('currency')
                                   ->label('Status')
                                   ->content(fn ($record) => ucfirst($record->currency))->columnSpan(6),
                        Placeholder::make('status')
                                   ->label('Status')
                                   ->content(fn ($record) => ucfirst($record->status)),
                        Placeholder::make('created_at')
                                   ->label('Created At')
                                   ->content(fn ($state) => \Carbon\Carbon::parse($state)->format('M d, Y H:i')),
                        Actions::make([
                            Action::make('openHostedLink')
                                  ->label('Download Receipt')
                                  ->icon('heroicon-o-arrow-top-right-on-square')
                                  ->url(fn ($record) => $record->hosted_url)
                                  ->openUrlInNewTab()
                                  ->visible(fn ($record) => filled($record->hosted_url)),
                        ]),
                        Actions::make([
                            Action::make('openPdfLink')
                                  ->label('Download PDF')
                                  ->icon('heroicon-o-document-arrow-down')
                                  ->url(fn ($record) => $record->pdf_url)
                                  ->openUrlInNewTab()
                                  ->visible(fn ($record) => filled($record->pdf_url)),
                        ]),
                    ])->columnSpan(1),
               ])
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
            'index' => Pages\ListInvoices::route('/'),
            'view' => Pages\ViewInvoice::route('/{record}'),
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
}
