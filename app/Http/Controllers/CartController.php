<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartDetail;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'cart_id' => "required|integer|numeric",
            'product_id' => 'required|integer|numeric',
            'no_items' => 'required|integer|numeric',
        ]);
        $product = Product::find($request->product_id);
        $quantity = $product->quantity;
        if ($request->no_items > $quantity)
        {
            return response()->json([
                'message' => 'The available quantity ='.$quantity.'.',
            ]);
        }
        $cart_detail = CartDetail::create([
            'cart_id' => $request->cart_id,
            'product_id' => $request->product_id,
            'no_items' => $request->no_items
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Product has been add to cart successfully',
            'cart_detail' => $cart_detail,
        ]);
    }
    public function remove()
    {
        
    }
    public function update()
    {
        
    }
    public function show()
    {
        
    }
}
