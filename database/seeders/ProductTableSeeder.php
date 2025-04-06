<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        // Create 5 suppliers if none exist
        if (Supplier::count() === 0) {
            Supplier::factory()->count(5)->create();
        }

        // Create 10 products with prices between 300-2000 PHP
        $products = [
            [
                'product_name' => 'Classic White T-Shirt',
                'size' => 'M',
                'category' => 'Mens',
                'types' => 'T-shirt',
                'description' => '100% cotton crew neck t-shirt with double stitching.',
                'cost_price' => 150.00,
                'sell_price' => 350.00,
                'stock' => 50,
                'supplier_id' => Supplier::first()->supplier_id,
            ],
            [
                'product_name' => 'Slim Fit Denim Jeans',
                'size' => 'L',
                'category' => 'Mens',
                'types' => 'Pants',
                'description' => 'Stretch denim jeans with modern slim fit design.',
                'cost_price' => 450.00,
                'sell_price' => 999.00,
                'stock' => 30,
                'supplier_id' => Supplier::first()->supplier_id,
            ],
            [
                'product_name' => 'Oversized Hoodie',
                'size' => 'XL',
                'category' => 'Womens',
                'types' => 'Hoodie',
                'description' => 'Cozy oversized hoodie with kangaroo pocket.',
                'cost_price' => 350.00,
                'sell_price' => 799.00,
                'stock' => 25,
                'supplier_id' => Supplier::skip(1)->first()->supplier_id,
            ],
            [
                'product_name' => 'Floral Print Summer Dress',
                'size' => 'S',
                'category' => 'Womens',
                'types' => 'Dress',
                'description' => 'Lightweight dress with vibrant floral pattern.',
                'cost_price' => 400.00,
                'sell_price' => 899.00,
                'stock' => 40,
                'supplier_id' => Supplier::skip(1)->first()->supplier_id,
            ],
            [
                'product_name' => 'Kids Cartoon Sweatshirt',
                'size' => 'XS',
                'category' => 'Kids',
                'types' => 'Sweatshirt',
                'description' => 'Colorful sweatshirt with cartoon character print.',
                'cost_price' => 200.00,
                'sell_price' => 450.00,
                'stock' => 60,
                'supplier_id' => Supplier::skip(2)->first()->supplier_id,
            ],
            [
                'product_name' => 'Premium Polo Shirt',
                'size' => 'L',
                'category' => 'Mens',
                'types' => 'Polo Shirt',
                'description' => 'High-quality pique polo with embroidered logo.',
                'cost_price' => 500.00,
                'sell_price' => 1200.00,
                'stock' => 20,
                'supplier_id' => Supplier::skip(2)->first()->supplier_id,
            ],
            [
                'product_name' => 'Athletic Shorts',
                'size' => 'M',
                'category' => 'Mens',
                'types' => 'Shorts',
                'description' => 'Quick-dry athletic shorts with built-in liner.',
                'cost_price' => 300.00,
                'sell_price' => 650.00,
                'stock' => 35,
                'supplier_id' => Supplier::skip(3)->first()->supplier_id,
            ],
            [
                'product_name' => 'Knit Sweater',
                'size' => 'M',
                'category' => 'Womens',
                'types' => 'Sweater',
                'description' => 'Chunky knit sweater for cold weather.',
                'cost_price' => 600.00,
                'sell_price' => 1500.00,
                'stock' => 15,
                'supplier_id' => Supplier::skip(3)->first()->supplier_id,
            ],
            [
                'product_name' => 'Basketball Jersey',
                'size' => 'XL',
                'category' => 'Mens',
                'types' => 'Jersey',
                'description' => 'Authentic replica basketball jersey with team logo.',
                'cost_price' => 700.00,
                'sell_price' => 1800.00,
                'stock' => 18,
                'supplier_id' => Supplier::skip(4)->first()->supplier_id,
            ],
            [
                'product_name' => 'Designer Denim Jacket',
                'size' => 'L',
                'category' => 'Womens',
                'types' => 'Hoodie',
                'description' => 'Trendy denim jacket with distressed details.',
                'cost_price' => 900.00,
                'sell_price' => 1999.00,
                'stock' => 12,
                'supplier_id' => Supplier::skip(4)->first()->supplier_id,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}