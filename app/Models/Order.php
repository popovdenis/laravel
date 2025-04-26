<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Order extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $fillable = ['user_id', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
