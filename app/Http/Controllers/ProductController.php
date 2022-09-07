<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
      public function index()
      {
        $products = Product::all();

        return response()->json($products, 200);
      }
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
    public function show($id){
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                'status' => 404,
                'errors' => 'Item Not Found!',
            ]);
        } else {
            return response()->json($product, 200);
        }
       
    }
///////////////////////////////////////////////
    public function delete($id)
    {
    /*    $product = product::find($id);
        if (is_null($product)) {
            return response()->json([
                'status' => 404,
                'errors' => 'Item Not Found!',
            ]);
        } else {
            $product->categories()->detach();
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'product deleted successfully',
            ]);
        }*/
        
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'brand_id' => ['required'],
            'price' => ['required', 'integer', 'max:999999'],
            'quantity' => ['required', 'integer', 'max:999'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['required'],
            'categories_ids' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = Product::find($id);
            $product->categories()->detach();
            $product->name = $request->name;
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->description = $request->description;
            $product->image = $request->image;
            $product->is_available = 1;
            $product->categories()->attach($request->categories_ids);
            $product->update();
            return response()->json([
                'status' => 200,
                'message' => 'Product updated successfully',
            ]);
        }
    }
    public function search($name)
    {
        $products = Product::where('name', 'like', '%'.$name.'%')->get();
        if (!count($products)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No Product found to be shown!',
            ]);
        }

        return response()->json($products, 200);
    }

}
