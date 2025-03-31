<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'itemable_id', 'itemable_type', 'quantity'];

    public function itemable()
    {
        return $this->morphTo();
    }
}
