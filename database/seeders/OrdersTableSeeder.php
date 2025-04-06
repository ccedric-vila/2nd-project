<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $statuses = ['pending', 'accepted', 'delivered', 'cancelled'];
        $users = User::limit(10)->get();

        // Create 10 orders between January-July 2025
        for ($i = 0; $i < 10; $i++) {
            $orderDate = Carbon::create(2025, rand(1, 7), rand(1, 28));
            
            Order::create([
                'user_id' => $users->random()->id,
                'total_amount' => 0, // Will be updated by OrderLines seeder
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
        }
    }
}