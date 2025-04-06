<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $categories = ['Mens', 'Womens', 'Kids'];
        $types = ['T-shirt', 'Polo Shirt', 'Sweater', 'Hoodie', 'Jersey', 'Dress', 'Sweatshirt', 'Pants', 'Shorts'];
        
        $costPrice = $this->faker->randomFloat(2, 5, 200);
        $markup = $this->faker->randomElement([1.3, 1.5, 1.7, 2.0]); // 30%, 50%, 70%, 100% markup
        $sellPrice = round($costPrice * $markup, 2);

        return [
            'product_name' => $this->generateProductName($types),
            'size' => $this->faker->randomElement($sizes),
            'category' => $this->faker->randomElement($categories),
            'types' => $this->faker->randomElement($types),
            'description' => $this->generateDescription(),
            'cost_price' => $costPrice,
            'sell_price' => $sellPrice,
            'stock' => $this->faker->numberBetween(0, 200),
            'supplier_id' => Supplier::inRandomOrder()->first()->supplier_id ?? Supplier::factory()->create()->supplier_id,
        ];
    }

    private function generateProductName($types): string
    {
        $styles = ['Premium', 'Classic', 'Trendy', 'Sporty', 'Vintage'];
        $materials = ['Cotton', 'Denim', 'Silk', 'Wool', 'Polyester'];
        $colors = ['Black', 'White', 'Blue', 'Red', 'Navy'];

        return implode(' ', [
            $this->faker->randomElement($styles),
            $this->faker->randomElement($materials),
            $this->faker->randomElement($colors),
            $this->faker->randomElement($types)
        ]);
    }

    private function generateDescription(): string
    {
        $features = [
            'Breathable fabric',
            'Moisture-wicking',
            'Stretchable material',
            'Wrinkle-resistant',
            'Eco-friendly'
        ];

        return sprintf(
            "%s %s. %s. %s.",
            $this->faker->sentence(6),
            $this->faker->randomElement($features),
            $this->faker->randomElement($features),
            $this->faker->randomElement([
                'Ideal for everyday wear',
                'Perfect for special occasions',
                'Great for athletic activities'
            ])
        );
    }
}