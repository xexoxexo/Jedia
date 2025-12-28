<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPromo;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPromo>
 */
class ProductPromoFactory extends Factory
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
            'promo_id' => Promo::all()->random()->id,
            'product_id' => $product_id,
            'discount' => $this->faker->numberBetween(25, 40),
        ];
    }
}
