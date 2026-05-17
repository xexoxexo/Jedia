<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $promoData = [
            ['New Year Blast', 70],
            ['Summer Flash Sale', 35],
            ['Weekend Saver', 20],
            ['Midnight Madness', 50],
            ['Super Payday Promo', 40],
            ['Member Appreciation', 25],
            ['Family Bundle Deal', 30],
            ['Limited Cashback', 15],
            ['Smart Shopper Week', 45],
            ['Beauty Essentials Fair', 55],
            ['Gadget Upgrade Days', 60],
            ['Home Living Festival', 35],
            ['Back to Campus', 30],
            ['Ramadan Savings', 50],
            ['Holiday Special', 65],
            ['Brand Mega Campaign', 55],
            ['Buy More Save More', 25],
            ['Food & Beverage Fiesta', 20],
            ['Sports Active Promo', 40],
            ['Closing Quarter Deal', 50],
        ];

        foreach ($promoData as $index => [$promoName, $discount]) {
            Promo::create([
                'promo_name' => $promoName,
                'promo_image' => 'img/promos/default-' . ($index + 1) . '.png',
                'promo_description' => $promoName . ' up to ' . $discount . '% off.',
            ]);
        }
    }
}
