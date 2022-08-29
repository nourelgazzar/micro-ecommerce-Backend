<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsToMany('App\Models\Category');
    }
    public function product()
    {
        return $this->belongsToMany('App\Models\Product');
    }
}
