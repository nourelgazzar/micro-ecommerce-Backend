<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function cart()
    {
        return $this->belongsTo('App\Models\Cart');
    }
}
