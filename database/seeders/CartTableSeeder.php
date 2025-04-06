<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing cart items
        Cart::truncate();

        // Get users and products
        $users = User::limit(10)->get();
        $products = Product::limit(30)->get();

        // Create 10 cart items
        foreach ($users as $user) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $products->random()->product_id,
                'quantity' => rand(1, 5),
            ]);
        }
    }
}