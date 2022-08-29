<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function brand()
    {
        return $this->hasMany('App\Models\Brand');
    }
    public function cateogryProduct()
    {
        return $this->belongsToMany('App\Models\CategoryProduct');
    }
    public function cart()
    {
        return $this->belongsToMany('App\Models\Cart');
    }
    public function orderDetails()
    {
        return $this->belongsTo('App\Models\OrderDetails');
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
