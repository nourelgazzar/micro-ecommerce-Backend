<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:40|regex:/(^([a-zA-Z]+)(\d+)?$)/u',
            'brand_id' => 'required',
            'price' => 'required|integer|max:999999',
            'quantity' => 'required|integer|max:999',
            'description' => 'required|string|max:500',
            'image' => 'required',
            'categories_ids' => 'required',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->image = $request->image;
        $product->is_available = 1;

        $product->save();

        $product->categories()->attach($request->categories_ids);

        return response()->json([
            'status' => 201,
            'message' => 'Product created successfully',
        ]);
    }
}
