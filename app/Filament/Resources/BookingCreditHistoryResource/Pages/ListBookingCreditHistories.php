<?php

namespace App\Filament\Resources\BookingCreditHistoryResource\Pages;

use App\Filament\Resources\BookingCreditHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingCreditHistories extends ListRecords
{
    protected static ?string $title = 'Booking Credit History';

    protected static string $resource = BookingCreditHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
