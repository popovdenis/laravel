<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @var array
     */
    public $casts = [
        'status' => 'boolean',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected $fillable = ['user_id', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
