<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

/**
 * Class SubscriptionTransactions
 *
 * @package App\Models
 */
class SubscriptionTransaction extends Model
{
    protected $fillable = ['user_id', 'subscription_id', 'credits_amount', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
