<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderLinesTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrderLine::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $orders = Order::all();
        $products = Product::limit(30)->get();

        // Create 2-4 order lines for each order
        foreach ($orders as $order) {
            $itemsCount = rand(2, 4);
            $total = 0;
            
            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->sell_price;
                
                OrderLine::create([
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'sell_price' => $price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at,
                ]);
                
                $total += $price * $quantity;
            }
            
            // Update order total amount
            $order->update(['total_amount' => $total]);
        }
    }
}