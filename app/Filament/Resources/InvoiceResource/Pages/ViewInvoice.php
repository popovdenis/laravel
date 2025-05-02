<?php
declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewInvoice
 *
 * @package App\Filament\Resources\InvoiceResource\Pages
 */
class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;
}
