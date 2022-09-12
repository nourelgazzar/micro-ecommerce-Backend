<?php

namespace App\Console;

use App\Http\Controllers\CartController;
use App\Models\Cart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
            $cart_ids = Cart::all()->value('id');
            $cart_ids_array = [];
            array_push($cart_ids_array, $cart_ids);
            foreach ($cart_ids_array as $cart_id) {
                $last_updated_at_record = DB::table('cart_details')->where('cart_id', '=', $cart_id)->orderBy('updated_at', 'desc')->first();
                $first = strtotime(now());
                $last = strtotime($last_updated_at_record->updated_at);

                if ($first - $last >= 60) {
                    (new CartController)->clear($cart_id);
                }
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
