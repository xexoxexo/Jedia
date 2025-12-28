<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductPromo;
use App\Models\ProductVariant;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'condition' => $this->faker->randomElement(['New', 'Used']),
            'merchant_id' => Merchant::all()->random()->id,
            'product_category_id' => ProductCategory::all()->random()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            ProductImage::factory()->create([
                'image' => function () {
                    $filename = uniqid() . '.jpg';

                    Storage::disk('public')->put('product-images/' . $filename, file_get_contents('https://source.unsplash.com/random'));

                    return 'storage/product-images/' . $filename;
                },
                'product_id' => $product->id,
            ]);
            ProductImage::factory()->create([
                'image' => function () {
                    $filename = uniqid() . '.jpg';

                    Storage::disk('public')->put('product-images/' . $filename, file_get_contents('https://source.unsplash.com/random'));

                    return 'storage/product-images/' . $filename;
                },
                'product_id' => $product->id,
            ]);
            ProductVariant::factory()->create([
                'name' => $this->faker->name(),
                'price' => $this->faker->numberBetween(1000, 100000),
                'stock' => $this->faker->numberBetween(0, 20),
                'product_id' => $product->id,
            ]);
            ProductVariant::factory()->create([
                'name' => $this->faker->name(),
                'price' => $this->faker->numberBetween(1000, 100000),
                'stock' => $this->faker->numberBetween(0, 20),
                'product_id' => $product->id,
            ]);
        });
    }
}
