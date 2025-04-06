<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Review::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get users who have completed orders
        $users = User::has('orders')->limit(10)->get();
        $products = Product::limit(20)->get();
        $orders = Order::where('status', 'delivered')->limit(15)->get();

        $comments = [
            'Great product, exactly as described!',
            'Good quality but shipping took longer than expected',
            'Perfect fit and very comfortable',
            'Not as pictured, but still decent quality',
            'Absolutely love it! Worth every peso',
            'The material feels cheap for the price',
            'Fast delivery and excellent packaging',
            'Size runs small, would recommend ordering up',
            'Beautiful design, very happy with my purchase',
            'Had some loose threads but otherwise okay'
        ];

        // Create 15 reviews
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $product = $products->random();
            $order = $orders->random();
            
            // Ensure the order contains the product
            if (!$order->orderLines()->where('product_id', $product->product_id)->exists()) {
                continue;
            }

            $reviewDate = Carbon::create(2025, rand(1, 7), rand(1, 28));

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->product_id,
                'order_id' => $order->id,
                'rating' => rand(1, 5),
                'comment' => $comments[array_rand($comments)],
                'created_at' => $reviewDate,
                'updated_at' => $reviewDate,
            ]);
        }
    }
}