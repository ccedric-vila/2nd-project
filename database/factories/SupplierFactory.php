<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'brand_name' => $this->faker->company . ' ' . $this->faker->randomElement([
                'Fashions', 'Apparel', 'Styles', 'Boutique', 'Collections',
                'Outfitters', 'Designs', 'Creations', 'Wear', 'Attire'
            ]),
            'email' => $this->faker->unique()->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress . "\n" . 
                        $this->faker->city . ', ' . 
                        $this->faker->stateAbbr . ' ' . 
                        $this->faker->postcode,
        ];
    }
}