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

        // Get users (limit to 10)
        $users = User::limit(10)->get();
        
        // Define the specific product IDs we want to use
        $productIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        
        // Create cart items for each user
        foreach ($users as $user) {
            // Since user_id must be unique, each user can only have one cart item
            // Get a random product from our specific IDs
            $product = Product::whereIn('product_id', $productIds)
                            ->inRandomOrder()
                            ->first();
            
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->product_id,
                'quantity' => $this->getRealisticQuantity($product),
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now()->subDays(rand(0, 30))
            ]);
        }
    }
    
    /**
     * Generate realistic quantities based on product type
     */
    protected function getRealisticQuantity(Product $product): int
    {
        // People typically buy more basic items (like t-shirts) than expensive ones
        if ($product->sell_price < 500) {
            return rand(1, 3); // 1-3 for inexpensive items
        } elseif ($product->sell_price < 1000) {
            return rand(1, 2); // 1-2 for mid-range items
        }
        return 1; // Just 1 for expensive items
    }
}