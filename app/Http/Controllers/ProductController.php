<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => array('required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'),
            'brand_id' => array('required'),
            'price' => array('required', 'integer', 'max:999999'),
            'quantity' => array('required', 'integer', 'max:999'),
            'description' => array('required', 'string', 'max:500'),
            'image' => array('required'),
            'categories_ids' => array('required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
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
}
