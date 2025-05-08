<?php

namespace Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource;

class ListBookingManagement extends ListRecords
{
    protected static string $resource = BookingManagementResource::class;
    protected static ?string $title = 'Booking Management';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Schedule Booking'),
        ];
    }
}
