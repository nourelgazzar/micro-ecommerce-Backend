<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Support\Facades\DB;

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


    public function delete($product_id)
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


    public function update($cart_id)
    {
        $cart_details = CartDetail::where('cart_id', '=', $cart_id);
        foreach($cart_details as $cart_detail)
        {
            $product_quantity = Product::find($cart_detail->product_id)->value('quantity');
            if ($product_quantity < $cart_detail->no_items)
            {
                $cart_detail->no_items = $product_quantity;
                $cart_detail->save();
            }
        }
    }


    public function show($cart_id)
    {
        $cart_details = CartDetail::where('cart_id', '=', $cart_id);
        foreach($cart_details as $cart_detail)
        {
            $product_quantity = Product::find($cart_detail->product_id)->value('quantity');
            if ($product_quantity < $cart_detail->no_items)
            {
                $cart_detail->no_items = $product_quantity;
                $cart_detail->save();
            }
        }
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
        
        $product_quantity = Product::find($request->product_id)->value('quantity');
        
        if ($request->no_items > $product_quantity)
        {
            DB::table('cart_details')->where('cart_id', $request->cart_id)->where('product_id', $request->product_id)->update(['no_items' => $product_quantity]);
            
        }
        else
        {
            DB::table('cart_details')->where('cart_id', $request->cart_id)->where('product_id', $request->product_id)->update(['no_items' => $request->no_items]);
        }
        return response()->json([
            'status' => 200,
        ]);

    }


    public function clear($cart_id)
    {
        $cart_detail = CartDetail::where('cart_id', '=', $cart_id);
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
