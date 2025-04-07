<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderLinesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have orders and products
        if (Order::count() === 0) {
            $this->call(OrdersTableSeeder::class);
        }

        if (Product::count() === 0) {
            $this->call(ProductTableSeeder::class);
        }

        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            // Each order has 1-5 products
            $itemsCount = rand(1, 5);
            $totalAmount = 0;
            $selectedProducts = [];

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                
                // Ensure unique products per order
                while (in_array($product->product_id, $selectedProducts)) {
                    $product = $products->random();
                }
                
                $selectedProducts[] = $product->product_id;
                
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

                $totalAmount += $quantity * $price;
            }

            // Update order total amount
            $order->total_amount = $totalAmount;
            $order->save();
        }
    }
}