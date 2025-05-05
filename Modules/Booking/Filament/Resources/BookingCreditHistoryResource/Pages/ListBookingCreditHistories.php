<?php

namespace Modules\Booking\Filament\Resources\BookingCreditHistoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Booking\Filament\Resources\BookingCreditHistoryResource;

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
