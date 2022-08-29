<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function orderDetails()
    {
        return $this->hasOne('App\Models\OrderDetails');
    }
    public function product()
    {
        return $this->belongsToMany('App\Models\Product');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
