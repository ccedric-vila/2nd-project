<?php

namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $images = [
            // Product ID 7
            [
                'product_id' => 7,
                'image_path' => 'product/5RuVTNDZrned1eeFnuZTu1PQly4vqu2lbQidiMD5.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 7,
                'image_path' => 'product/XBnXnPZ0yWHELxtZbWwgNDj9c7hV0uJdh1nh16oA.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 9
            [
                'product_id' => 9,
                'image_path' => 'product/tyB4gBCiTaP3S9DQHRmfM7v4Z2p47yMtkXB7jFDK.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 9,
                'image_path' => 'product/KDOgpdkLGSS4hScxoU26TB3n1uj5QvetyQKT0phN.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 1
            [
                'product_id' => 1,
                'image_path' => 'product/7AVetzAMIQriyuetdP8bUR74F3zeYfT667kJKL20.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 1,
                'image_path' => 'product/CLRiutFdjsXWsIyonmjLAHLaQxhv88xEyIzzM0G5.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 10
            [
                'product_id' => 10,
                'image_path' => 'product/dSo7GkICVHMgSPKK4y55i7BvBNj4zgeKHSWSXXEj.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 10,
                'image_path' => 'product/f6sPk5862OrgkZEJtKEcfRKPHNlfzvvH70M1hd0f.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 4
            [
                'product_id' => 4,
                'image_path' => 'product/cvvXv2qijwW0rxGVvnQISmbsWCsGvzUSqEcWkkTf.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 4,
                'image_path' => 'product/Ntx4LDYRJENbamUK2Lnro3TkOUONDtM7EarXjmCu.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 4,
                'image_path' => 'product/q6A87HjyrNE2fzbD2aMv1buwp0ns5aSTJCzyuvwi.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 6
            [
                'product_id' => 6,
                'image_path' => 'product/osmjknapSP6WxDRAMhknE98iLy5AJULc9YmruoAs.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 6,
                'image_path' => 'product/fplxMfOjpzpSPjRvyTlLZjoIKl70eKHKBKSpnlmN.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 2
            [
                'product_id' => 2,
                'image_path' => 'product/0kLKGOdbb3qbxQv3R8Jtk0ker5l7ILPkbsClnFzF.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 2,
                'image_path' => 'product/kF1ygwvyRw96XM5vz7Rk7kNK2utvMfk8UOhnFYme.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 5
            [
                'product_id' => 5,
                'image_path' => 'product/cJVCp4bPekDCaywDs1Gqmr6J5x94zkKHl0KkbVdd.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 5,
                'image_path' => 'product/QQwfH6CuPhgvX25CYtVaMSCI6gdxIU809JfHKWEg.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 5,
                'image_path' => 'product/V93kjCtMbAzo31maxCAmsBYAHAhC5A55yYrNSVzf.jpg',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 8
            [
                'product_id' => 8,
                'image_path' => 'product/OfsxL3f0djL0j4elkc6JSwmR1Q4M3XM8HN6yG6bS.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 8,
                'image_path' => 'product/ksW5FKpRoPPINRiQ17CPxKxHA4UJY7JjBrB7ujfL.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            
            // Product ID 3
            [
                'product_id' => 3,
                'image_path' => 'product/wM9weM7Ais9YEArGwRjz2s0BEXlOT9tsR8uNISLV.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 3,
                'image_path' => 'product/BCM1qWiJYZx8nXQ0l0k168LEOMYuTMTlalVfbTbW.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 3,
                'image_path' => 'product/lQlKv7UhDKgunrrRQ7hHVIe8cdwihJ7JM7hZ9pFR.png',
                'is_primary' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        // Insert all images
        DB::table('product_images')->insert($images);

        // Set one primary image per product
        $productIds = array_unique(array_column($images, 'product_id'));
        foreach ($productIds as $productId) {
            DB::table('product_images')
                ->where('product_id', $productId)
                ->orderBy('id')
                ->limit(1)
                ->update(['is_primary' => 1]);
        }
    }
}