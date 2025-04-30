<?php
declare(strict_types=1);

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Payment
 *
 * @package Modules\Payment\Models
 */
class Payment extends Model
{
    protected $fillable = [
        'amount',
        'currency',
        'status',
        'method',
        'transaction_id',
        'paid_at',
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
}
