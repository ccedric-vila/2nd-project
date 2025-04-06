<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks for truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Sale::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get only delivered orders with their order lines
        $deliveredOrders = Order::with('orderLines')
                               ->where('status', 'delivered')
                               ->get();

        // Create sales records for delivered orders
        foreach ($deliveredOrders as $order) {
            foreach ($order->orderLines as $orderLine) {
                // Set sale date 1-3 days after order creation (simulating delivery time)
                $saleDate = $order->created_at->addDays(rand(1, 3));
                
                Sale::create([
                    'order_id' => $order->id,
                    'order_line_id' => $orderLine->id,
                    'product_id' => $orderLine->product_id,
                    'user_id' => $order->user_id,
                    'quantity' => $orderLine->quantity,
                    'unit_price' => $orderLine->sell_price,
                    // Note: total_price is computed automatically (quantity * unit_price)
                    'sale_date' => $saleDate->toDateString(),
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);
            }
        }

        // If no delivered orders exist, create some sample sales
        if (Sale::count() === 0) {
            $this->createSampleSales();
        }
    }

    protected function createSampleSales()
    {
        $orders = Order::with('orderLines')->take(5)->get();
        $products = \App\Models\Product::take(10)->get();
        $users = \App\Models\User::take(5)->get();

        foreach (range(1, 10) as $i) {
            $saleDate = Carbon::now()->subDays(rand(1, 30));
            
            // 50% chance to use an existing order line
            if (rand(0, 1) && $orders->isNotEmpty()) {
                $order = $orders->random();
                $orderLine = $order->orderLines->random();
                
                Sale::create([
                    'order_id' => $order->id,
                    'order_line_id' => $orderLine->id,
                    'product_id' => $orderLine->product_id,
                    'user_id' => $order->user_id,
                    'quantity' => $orderLine->quantity,
                    'unit_price' => $orderLine->sell_price,
                    'sale_date' => $saleDate->toDateString(),
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);
            } else {
                $product = $products->random();
                $user = $users->random();
                
                Sale::create([
                    'order_id' => null, // Direct sale
                    'order_line_id' => null,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'quantity' => rand(1, 5),
                    'unit_price' => $product->sell_price,
                    'sale_date' => $saleDate->toDateString(),
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);
            }
        }
    }
}