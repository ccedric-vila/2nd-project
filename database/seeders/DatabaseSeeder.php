<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            SupplierTableSeeder::class,
            ProductTableSeeder::class,
            OrdersTableSeeder::class,
            OrderLinesTableSeeder::class,
            ReviewsTableSeeder::class,
            SalesTableSeeder::class,
            CartTableSeeder::class,
            // Add other seeders if needed
        ]);
    }
}