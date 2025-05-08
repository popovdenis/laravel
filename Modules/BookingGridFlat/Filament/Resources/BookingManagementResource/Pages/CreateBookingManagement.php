<?php

namespace Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource;

class CreateBookingManagement extends CreateRecord
{
    protected static string $resource = BookingManagementResource::class;
    protected static ?string $title = 'Create Booking';
}
