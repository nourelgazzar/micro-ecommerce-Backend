<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products, 200);
    }

    public function store(Request $request)
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

    public function show($id)
    {
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
        $product = product::find($id);
        if (is_null($product)) {
            return response()->json([
                'status' => 404,
                'errors' => 'Item Not Found!',
            ]);
        }
        $product->categories()->detach();
        $product->delete();

        return response()->json([
            'status' => 200,
            'message' => 'product deleted successfully',
        ]);
    }

    public function update(Request $request, $id)
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

    public function filter_and_search(Request $request)
    {
        $query = collect();
        if (! empty($request->product_name)) {
            $products = Product::where('name', 'like', '%'.$request->product_name.'%')->get();
            $query = $query->merge($products);
            $query = $query->unique(function ($entry) {
                return $entry;
            });
        }
        if (! empty($request->brand)) {
            $brand_id = Brand::where('name', '=', $request->brand)->value('id');
            $products = Product::where('brand_id', '=', $brand_id)->get();
            $query = $query->merge($products);
            $query = $query->unique(function ($entry) {
                return $entry;
            });
        }
        if (! empty($request->category)) {
            $category_id = Category::where('name', '=', $request->category)->value('id');

            $product_categories = DB::table('category_product')->where('category_id', '=', $category_id)->value('product_id');
            $arr = [];
            array_push($arr, $product_categories);

            foreach ($arr as $id) {
                $product = Product::where('id', '=', $id)->get();
                $query = $query->merge($product);
            }
            $query = $query->unique(function ($entry) {
                return $entry;
            });
        }
        if (! empty($request->price_min) && ! empty($request->price_max)) {
            $products = DB::table('products')
                            ->whereBetween('price', [$request->price_min, $request->price_max])
                            ->get();
            $query = $query->merge($products);
            $query = $query->unique(function ($entry) {
                return $entry;
            });
        }

        return response()->json($query, 200);
    }
}
