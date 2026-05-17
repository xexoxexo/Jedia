<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchant>
 */
class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $userIds = null;
        $userIds = $userIds ?? User::pluck('id')->all();
        $user_id = $this->faker->unique()->randomElement($userIds);

        return [
            'name' => $this->faker->name(),
            'image' => 'img/logo/logo.png',
            'banner_image' => 'img/logo/banner-merchant.jpeg',
            'description' => $this->faker->text(50),
            'catch_phrase' => $this->faker->words(10, true),
            'full_description' => $this->faker->sentence(18),
            'user_id' => $user_id,
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Merchant $merchant) {
            Location::create([
                'city' => $this->faker->city(),
                'country' => $this->faker->country(),
                'address' => $this->faker->address(),
                'notes' => $this->faker->citySuffix(),
                'postal_code' => $this->faker->postcode(),
                'locationable_type' => 'merchant',
                'locationable_id' => $merchant->id,
                'latitude' => $this->faker->latitude(),
                'longitude' => $this->faker->longitude(),
            ]);
        });
    }
}
