<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
    public function cateogryProduct()
    {
        return $this->belongsToMany('App\Models\CategoryProduct');
    }
    public function cartDetails()
    {
        return $this->belongsToMany('App\Models\CartDetail');
    }
    public function orderDetails()
    {
        return $this->belongsToMany('App\Models\OrderDetails');
    }
    public function order()
    {
        return $this->belongsToMany('App\Models\Order');
    }
}
