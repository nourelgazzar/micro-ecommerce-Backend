<?php

namespace App\Console;

use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $data = date('H:i:s', strtotime(now())-60);
            $carts = Cart::join('cart_details', 'carts.id', '=', 'cart_details.cart_id')->whereTime('cart_details.updated_at', '<=',$data)->orderBy('cart_details.updated_at', 'desc')->get();
            $carts->toArray();
            $cart_id_check = 0;
            foreach ($carts as $cart) {
                
                if ($cart_id_check != $cart->cart_id) {
                    $cart_details = CartDetail::where('cart_id', '=', $cart->cart_id)->get();
                    $cart_details->toArray();
                    foreach ($cart_details as $cart_detail) {
                        CartDetail::where('cart_id', $cart_detail->cart_id)->where('product_id', $cart_detail->product_id)->delete();
                    }
                }
                $cart_id_check = $cart->cart_id;
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
