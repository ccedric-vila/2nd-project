<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

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
                // Generate random sale date within the same year as order creation
                $year = $order->created_at->year;
                $month = rand(1, 12);
                $day = rand(1, 28);
                $saleDate = Carbon::create($year, $month, $day)->toDateString();

                Sale::create([
                    'order_id' => $order->id,
                    'order_line_id' => $orderLine->id,
                    'product_id' => $orderLine->product_id,
                    'user_id' => $order->user_id,
                    'quantity' => $orderLine->quantity,
                    'unit_price' => $orderLine->sell_price,
                    'sale_date' => $saleDate,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at,
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