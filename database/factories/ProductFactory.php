<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $merchantIds = null;
        static $categoryIds = null;

        $merchantIds = $merchantIds ?? Merchant::pluck('id')->all();
        $categoryIds = $categoryIds ?? ProductCategory::pluck('id')->all();

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->text(),
            'condition' => $this->faker->randomElement(['New', 'Used']),
            'merchant_id' => $this->faker->randomElement($merchantIds),
            'product_category_id' => $this->faker->randomElement($categoryIds),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            ProductImage::factory()->create([
                'image' => 'img/logo/logo.png',
                'product_id' => $product->id,
            ]);
            ProductImage::factory()->create([
                'image' => 'img/logo/logo.png',
                'product_id' => $product->id,
            ]);
            ProductVariant::factory()->create([
                'name' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
                'price' => $this->faker->numberBetween(1000, 100000),
                'stock' => $this->faker->numberBetween(1, 300),
                'product_id' => $product->id,
            ]);
            ProductVariant::factory()->create([
                'name' => $this->faker->randomElement(['Black', 'White', 'Blue', 'Red']),
                'price' => $this->faker->numberBetween(1000, 100000),
                'stock' => $this->faker->numberBetween(1, 300),
                'product_id' => $product->id,
            ]);
        });
    }
}
