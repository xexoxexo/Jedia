<?php

namespace Database\Factories;

use App\Models\FlashSaleProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashSaleProduct>
 */
class FlashSaleProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $product_id = $this->faker->unique()->randomElement(Product::pluck('id'));

        return [
            'product_id' => $product_id,
            'discount' => $this->faker->numberBetween(50, 100),
        ];
    }
}
