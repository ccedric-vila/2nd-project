<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure users exist
        if (User::where('role', 'user')->count() === 0) {
            $this->call(UsersTableSeeder::class);
        }

        $users = User::where('role', 'user')->get();

        // Create 15 delivered orders
        $this->createOrders($users, 15, ['delivered']);
        
        // Create 15 accepted orders
        $this->createOrders($users, 15, ['accepted']);
        
        // Create 10 cancelled orders
        $this->createOrders($users, 10, ['cancelled']);
    }

    protected function createOrders($users, $count, $statuses)
    {
        for ($i = 0; $i < $count; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            
            // Randomly select year (2024 or 2025)
            $year = rand(0, 1) ? 2024 : 2025;
            // Random month between January (1) and July (7)
            $month = rand(1, 7);
            // Random day (1-28 to avoid month length issues)
            $day = rand(1, 28);
            // Random hour
            $hour = rand(0, 23);
            // Random minute
            $minute = rand(0, 59);
            
            $createdAt = Carbon::create($year, $month, $day, $hour, $minute, 0);

            Order::create([
                'user_id' => $user->id,
                'total_amount' => 0, // Will be updated by OrderLines seeder
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $this->getUpdatedAt($status, $createdAt),
            ]);
        }
    }

    protected function getUpdatedAt($status, $createdAt)
    {
        return match($status) {
            'accepted' => $createdAt->copy()->addHours(rand(1, 12)),
            'delivered' => $createdAt->copy()->addDays(rand(1, 5)),
            'cancelled' => $createdAt->copy()->addHours(rand(1, 24)),
            default => $createdAt,
        };
    }
}