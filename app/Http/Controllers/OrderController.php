<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller{
   public function show($user_id)
   {
    $user=User::find($user_id);
    if($user==null){
        return response()->json([
            'status' => 404,
            'message' => "No User found",
        ]);
      }
    $order_listing=array();
    $mid_array=array();
    //list of prders

      $orders = Order::where('user_id', 'like', '%'.$user_id.'%')->get();
      if(count($orders)==0){
        return response()->json([
            'status' => 404,
            'message' => "No Orders found",
        ]);
      }
      foreach($orders as $order)
      {
        $products=array();
        //List of Order Details
        $order_details=OrderDetail::where('order_id', 'like', '%'.$order->id.'%')->get();
        foreach($order_details as $order_detail)
        {
            $product = Product::find($order_detail->product_id);
            $product->number_items=$order_detail->number_items;
            array_push($products,$product);
           
        }

        $mid_array['total_price']=$order->price;
        $mid_array['list_items']=$products;
        array_push($order_listing,$mid_array);
      }


  return response()->json([
            'status' => 200,
            'orders' => $order_listing,
        ]);
   }

   public function create(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'cart_id' => ['required'],
        'user_id' => ['required'],

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages(),
        ]);
    } 

    $user = User::find($request->user_id); 
    if($user == null){
        return response()->json([
            'status' => 404,
            'errors' => "User Not Found",
        ]);
    }
     //List of rows 
    $cart_details=CartDetail::where('cart_id', 'like', '%'.$request->cart_id.'%')->get();
    if(count($cart_details)==0){
        return response()->json([
            'status' => 404,
            'order' => "Your Cart Is Empty!",
        ]);
    }
    $order = new Order;
    $order->user_id = $request->user_id;
    $order->price = 0;
    $order->invoice_number = rand(999,9999);;
    $order->save();
    $total_price=0;
    foreach ($cart_details as $cart_detail)
    { 
        $order_details = new OrderDetail; 
        $order_details->order_id = $order->id;
        $order_details->product_id = $cart_detail->product_id;
        $order_details->number_items = $cart_detail->no_items;
        $product = Product::find($cart_detail->product_id);
        $total_price = $total_price +  ($product->price*$cart_detail->no_items);
        $order_details->price = $product->price;
        $order_details->save();
    }
    if($total_price > $user->balance){
       $this->delete($order->id);
        return response()->json([
            'status' => 400,
            'message' => "Create order Failed Because you do not have enough money in your wallet",
        ]);
    }
    $user->balance = $user->balance-$total_price;
    $user->update();
    $order_new = Order::find($order->id);
    $order_new->price = $total_price;
    $order_new->update();

    return response()->json([
        'status' => 200,
        'order' => $order_new,
    ]);
   }

   public function delete($id)
   {
     $order = Order::find($id);
     if(is_null($order)){
        return response()->json([
            'status' => 404,
            'order' => "Order Not Found",
        ]);
     }
    
     $order_details = OrderDetail::where('order_id', 'like', '%'.$id.'%')->get();
     foreach ($order_details as $order_detail)
     { 
        $order_detail->delete();
     }
     $order->delete();
     return response()->json([
        'status' => 200,
        'order' =>"Order Deleted Successfully",
     ]);
    
   }




}