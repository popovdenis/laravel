<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int|null $booking_id
 * @property int $user_id
 * @property int $credits_amount
 * @property string $action
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @method static Builder spend()
 * @method static Builder refund()
 * @method static Builder adjustment()
 */
class BookingCreditHistory extends Model
{
    protected $table = 'booking_credit_history';

    protected $fillable = [
        'booking_id',
        'user_id',
        'credits_amount',
        'action',
    ];

    protected $casts = [
        'credits_amount' => 'integer',
        'created_at'     => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSpend(Builder $query): Builder
    {
        return $query->where('action', 'spend');
    }

    public function scopeRefund(Builder $query): Builder
    {
        return $query->where('action', 'refund');
    }

    public function scopeAdjustment(Builder $query): Builder
    {
        return $query->where('action', 'adjustment');
    }
}
