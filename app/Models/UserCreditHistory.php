<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCreditHistory
 *
 * @package App\Models
 */
class UserCreditHistory extends Model
{
    protected $table = 'user_credit_history';

    protected $fillable = ['user_id', 'credits_amount', 'source', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
