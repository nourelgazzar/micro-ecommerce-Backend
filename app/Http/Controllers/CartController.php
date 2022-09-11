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
    public function remove($product_id)
    {
        $cart_detail = CartDetail::where('product_id', '=', $product_id);
        if (is_null($cart_detail) || empty($cart_detail)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No product found to be removed!',
            ]);
        } else {
            $cart_detail->delete();

            return response()->json([
                'status' => 200,
                'message' => 'The product has been removed from the cart!',
            ]);
        }
    }
    public function update()
    {
        
    }
    public function index($cart_id)
    {
        $cart_details = CartDetail::where('cart_id', '=', $cart_id);
        return $cart_details;
    }
    public function edit(Request $request)
    {
        
        $this->validate($request, [
            'cart_id' => "required|integer|numeric",
            'product_id' => 'required|integer|numeric',
            'no_items' => 'required|integer|numeric',
        ]);
        $cart_detail = CartDetail::where('product_id', '=', $request->product_id)->where('cart_id', '=', $request->cart_id);
        if (!$cart_detail || empty($cart_detail)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No category found to be updated!',
            ]);
        }
        $cart_detail->no_items = $request->no_items;
        $cart_detail->save();

        return response()->json([
            'status' => 200,
            'category' => $cart_detail,
        ]);

    }
    public function clear($cart_id)
    {
        $cart_detail = CartDetail::where('cart_id', '=', $cart_id)->first();
        while($cart_detail || !empty($cart_detail))
        {
            $cart_detail->delete();
        }
        return response()->json([
            'status' => 200,
            'message' => 'The cart has been cleared!',
        ]);
    }
}
