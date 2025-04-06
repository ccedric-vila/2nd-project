<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        
        return [
            'name' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'image' => null,
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
            'age' => $this->faker->numberBetween(18, 65),
            'sex' => $gender,
            'contact_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'email_verified_at' => now(),
            // Removed remember_token
        ];
    }
}