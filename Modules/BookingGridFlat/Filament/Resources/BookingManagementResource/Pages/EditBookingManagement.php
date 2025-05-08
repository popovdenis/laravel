<?php

namespace Modules\BookingGridFlat\Filament\Resources\BookingManagementResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\BookingGridFlat\Filament\Resources\BookingManagementResource;

class EditBookingManagement extends EditRecord
{
    protected static string $resource = BookingManagementResource::class;
    protected static ?string $title = 'Update Booking';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
