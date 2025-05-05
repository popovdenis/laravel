<?php

namespace Modules\Order\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Order\Filament\Resources\OrderResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'Order View';
}
