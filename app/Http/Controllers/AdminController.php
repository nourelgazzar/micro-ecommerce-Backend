<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class AdminController extends Controller
{
    public function analytics()
    {
        $total_prices = 0;
        $orders = Order::all();
        foreach ($orders as $order) {
            $total_prices += $order->price;
        }

        $products = Product::pluck('id');
        $max_items = 0;
        $max_items_id = 0;
        $brands_number_of_sold_items = array_fill(0, 100, 0);

        foreach ($products as $product) {
            $add_items = 0;
            $products_orders = OrderDetail::where('product_id', '=', $product)->get();
            foreach ($products_orders as $product_order) {
                $add_items += $product_order->number_items;
                $id = Product::find($product_order->product_id)->value('brand_id');
                $brands_number_of_sold_items[$id] += $product_order->number_items;
            }
            if ($add_items > $max_items) {
                $max_items = $add_items;
                $max_items_id = $product;
            }
        }

        $max_items_of_brands = 0;
        $max_items_of_brands_id = 0;
        for ($i = 1; $i < 100; $i++) {
            if ($brands_number_of_sold_items[$i] > $max_items_of_brands) {
                $max_items_of_brands = $brands_number_of_sold_items[$i];
                $max_items_of_brands_id = $i;
            }
        }

        return response()->json([
            'status' => 200,
            'total_prices' => $total_prices,
            'most sold product' => Product::find($max_items_id),
            'number of items of most sold product' => $max_items,
            'most sold brand' => Brand::find($max_items_of_brands_id),
            'number of items of most sold brand' => $max_items_of_brands,

        ]);
    }
}
