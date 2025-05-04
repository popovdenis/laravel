<?php
declare(strict_types=1);

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\CronSchedule\Models\CronSchedule;
use Modules\Order\Models\Order;
use Modules\User\Models\User;

/**
 * Class Invoice
 *
 * @package Modules\Invoice\Models
 */
class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'stripe_id',
        'amount_due',
        'due_date',
        'invoice_created_at',
        'effective_at',
        'amount',
        'is_paid',
        'currency',
        'hosted_url',
        'pdf_url',
        'increment_id',
        'status',
        'amount_paid',
        'subtotal',
        'total',
        'tax',
        'total_excl_tax',
    ];

    protected $casts = [
        'is_paid'   => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
