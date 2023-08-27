<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_amount', 'shipping_fee','discount_amount'];

    public function orderedItem()
    {
        return $this->hasMany('App\Models\orderItem', 'order_id','id');
    }

}
