<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        'cart_id',
        'product_id',
        'no_items',
    ];
    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function cart()
    {
        return $this->belongsTo('App\Models\Cart');
    }
}
