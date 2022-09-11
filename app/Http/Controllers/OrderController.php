<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\OrderDetail;
use App\Models\Order;

class OrderController extends Controller{


   public function create(Request $request)
   {
    
     //List of rows 
    $cart_details=CartDetail::find($request->cart_id);

    $order = new Order;
   
    $total_price;
    foreach ($cart_details as $cart_detail)
    { 
        $order_details = new OrderDetail; 
        $order_details->order_id = $order->id;
        $order_details->product_id = $cart_details->product_id;
        $order_details->no_items = $cart_details->no_items;
        $product = Product::find($cart_details->product_id);
        $total_price = $total_price +  $product->price;
        $order_details->price = $product->price;
    }
    
    $order->user_id = $user_id;
    $order->price = $total_price;
    $order->invoice_number = "00";
    $order->save();

    return response()->json([
        'status' => 200,
        'order' => $order,
    ]);
   }


}