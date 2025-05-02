<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
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

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationGroup = 'Subscriptions';
    protected static ?string $label = 'Invoices';
    protected static ?string $slug = 'invoices';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Invoice Details')
                    ->schema([
                        TextInput::make('id')->disabled(),
                        ViewField::make('order_link')
                                 ->label('Order')
                                 ->view('components.filament.html-link')->columnSpanFull(),
                        Placeholder::make('order.user.name')
                                   ->label('Customer')
                                   ->content(fn ($record) => $record->order->user->getFullNameAttribute()),
                        Placeholder::make('amount_due')
                                   ->label('Amount Due')
                                   ->content(fn ($record) => $record->amount_due ?? __('None')),
                        Placeholder::make('currency')
                                   ->label('Status')
                                   ->content(fn ($record) => ucfirst($record->currency)),
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
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable()
                    ->toggleable(),


                TextColumn::make('amount_paid')
                    ->label('Grand Total (Purchased)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
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
