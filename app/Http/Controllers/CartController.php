<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'names' => "required|array|min:1",
            'names.*' => 'required|string|max:40|regex:/(^([a-zA-Z ]+)(\d+)?$)/u|unique:categories,name',
        ]);
    }
}
