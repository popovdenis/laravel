<?php
declare(strict_types=1);

namespace Modules\Invoice\Filament\Resources\InvoiceResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Invoice\Filament\Resources\InvoiceResource;

/**
 * Class ViewInvoice
 *
 * @package App\Filament\Resources\InvoiceResource\Pages
 */
class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected static ?string $title = 'View Invoice';
}
