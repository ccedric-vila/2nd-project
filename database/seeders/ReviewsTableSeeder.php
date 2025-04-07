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
        $reviews = [
            [
                'user_id' => 6,
                'product_id' => 8,
                'order_id' => 1,
                'rating' => 4,
                'comment' => 'Great product, arrived in perfect condition',
                'created_at' => Carbon::create(2025, 4, 15, 10, 22),
                'updated_at' => Carbon::create(2025, 4, 15, 10, 22)
            ],
            [
                'user_id' => 11,
                'product_id' => 3,
                'order_id' => 2,
                'rating' => 5,
                'comment' => 'Absolutely love this! Exceeded my expectations',
                'created_at' => Carbon::create(2025, 4, 24, 14, 35),
                'updated_at' => Carbon::create(2025, 4, 24, 14, 35)
            ],
            [
                'user_id' => 14,
                'product_id' => 9,
                'order_id' => 3,
                'rating' => 3,
                'comment' => 'Good quality but took longer to arrive than expected',
                'created_at' => Carbon::create(2025, 3, 2, 9, 15),
                'updated_at' => Carbon::create(2025, 3, 2, 9, 15)
            ],
            [
                'user_id' => 15,
                'product_id' => 6,
                'order_id' => 4,
                'rating' => 5,
                'comment' => 'Perfect! Will definitely order again',
                'created_at' => Carbon::create(2025, 2, 28, 18, 42),
                'updated_at' => Carbon::create(2025, 2, 28, 18, 42)
            ],
            [
                'user_id' => 11,
                'product_id' => 4,
                'order_id' => 5,
                'rating' => 4,
                'comment' => 'Very satisfied with this purchase',
                'created_at' => Carbon::create(2024, 7, 9, 11, 20),
                'updated_at' => Carbon::create(2024, 7, 9, 11, 20)
            ],
            [
                'user_id' => 16,
                'product_id' => 10,
                'order_id' => 6,
                'rating' => 2,
                'comment' => 'Product was okay but not worth the price',
                'created_at' => Carbon::create(2024, 6, 8, 16, 45),
                'updated_at' => Carbon::create(2024, 6, 8, 16, 45)
            ],
            [
                'user_id' => 5,
                'product_id' => 6,
                'order_id' => 7,
                'rating' => 5,
                'comment' => 'Excellent quality and fast shipping',
                'created_at' => Carbon::create(2025, 3, 7, 8, 30),
                'updated_at' => Carbon::create(2025, 3, 7, 8, 30)
            ],
            [
                'user_id' => 17,
                'product_id' => 9,
                'order_id' => 8,
                'rating' => 4,
                'comment' => 'Great product, minor packaging issue',
                'created_at' => Carbon::create(2025, 6, 29, 12, 10),
                'updated_at' => Carbon::create(2025, 6, 29, 12, 10)
            ],
            [
                'user_id' => 3,
                'product_id' => 8,
                'order_id' => 9,
                'rating' => 5,
                'comment' => 'Absolutely perfect!',
                'created_at' => Carbon::create(2025, 2, 22, 19, 25),
                'updated_at' => Carbon::create(2025, 2, 22, 19, 25)
            ],
            [
                'user_id' => 14,
                'product_id' => 2,
                'order_id' => 10,
                'rating' => 3,
                'comment' => 'Average product, nothing special',
                'created_at' => Carbon::create(2024, 3, 9, 14, 50),
                'updated_at' => Carbon::create(2024, 3, 9, 14, 50)
            ],
            [
                'user_id' => 16,
                'product_id' => 2,
                'order_id' => 11,
                'rating' => 4,
                'comment' => 'Good quality, happy with purchase',
                'created_at' => Carbon::create(2024, 5, 28, 9, 15),
                'updated_at' => Carbon::create(2024, 5, 28, 9, 15)
            ],
            [
                'user_id' => 4,
                'product_id' => 7,
                'order_id' => 12,
                'rating' => 5,
                'comment' => 'Fantastic! Better than expected',
                'created_at' => Carbon::create(2024, 4, 28, 11, 20),
                'updated_at' => Carbon::create(2024, 4, 28, 11, 20)
            ],
            [
                'user_id' => 19,
                'product_id' => 10,
                'order_id' => 13,
                'rating' => 1,
                'comment' => 'Product arrived damaged, very disappointed',
                'created_at' => Carbon::create(2025, 7, 16, 15, 40),
                'updated_at' => Carbon::create(2025, 7, 16, 15, 40)
            ],
            [
                'user_id' => 7,
                'product_id' => 8,
                'order_id' => 14,
                'rating' => 5,
                'comment' => 'Perfect in every way!',
                'created_at' => Carbon::create(2025, 5, 27, 10, 5),
                'updated_at' => Carbon::create(2025, 5, 27, 10, 5)
            ],
            [
                'user_id' => 14,
                'product_id' => 3,
                'order_id' => 15,
                'rating' => 4,
                'comment' => 'Very good product, would recommend',
                'created_at' => Carbon::create(2024, 2, 15, 13, 30),
                'updated_at' => Carbon::create(2024, 2, 15, 13, 30)
            ]
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}