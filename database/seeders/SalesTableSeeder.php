<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure order lines exist
        if (OrderLine::count() === 0) {
            $this->call(OrderLinesTableSeeder::class);
        }

        // Process both delivered AND accepted orders for sales
        $completedOrders = Order::with('orderLines')
            ->whereIn('status', ['delivered', 'accepted'])
            ->get();

        foreach ($completedOrders as $order) {
            foreach ($order->orderLines as $orderLine) {
                Sale::create([
                    'order_id' => $order->id,
                    'order_line_id' => $orderLine->id,
                    'product_id' => $orderLine->product_id,
                    'user_id' => $order->user_id,
                    'quantity' => $orderLine->quantity,
                    'unit_price' => $orderLine->sell_price,
                    'sale_date' => $order->updated_at->toDateString(),
                    'created_at' => $order->updated_at,
                    'updated_at' => $order->updated_at,
                ]);

                // Update product stock only for delivered orders
                if ($order->status === 'delivered') {
                    $product = $orderLine->product;
                    $product->stock -= $orderLine->quantity;
                    $product->save();
                }
            }
        }
    }
}