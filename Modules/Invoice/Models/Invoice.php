<?php
declare(strict_types=1);

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Models\Order;

/**
 * Class Invoice
 *
 * @package Modules\Invoice\Models
 */
class Invoice extends Model
{
    protected $fillable = [
        'order_id', 'stripe_invoice_id', 'status', 'amount',
        'currency', 'hosted_url', 'pdf_url',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
