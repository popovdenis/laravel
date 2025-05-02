<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Modules\Invoice\Models\Invoice;

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('order.id')
                    ->label('Order ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('order.user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => number_format($state / 100, 2))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'danger' => 'failed',
                        'warning' => 'open',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('hosted_url')
                    ->label('Hosted Link')
                    ->url(fn ($record) => $record->hosted_url, true)
                    ->openUrlInNewTab()
                    ->toggleable(),

                TextColumn::make('pdf_url')
                    ->label('PDF Link')
                    ->url(fn ($record) => $record->pdf_url, true)
                    ->openUrlInNewTab()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
