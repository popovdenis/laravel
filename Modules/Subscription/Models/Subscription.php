<?php

namespace Modules\Subscription\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id',
        'user_id',
        'canceled_immediately',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
    ];

    protected $casts = [
        'canceled_immediately' => 'boolean',
        'trial_ends_at'        => 'datetime',
        'starts_at'            => 'datetime',
        'ends_at'              => 'datetime',
        'canceled_at'          => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
