<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $promo1 = Promo::create([
            'promo_name' => "New Year's Sale",
            'promo_image' => 'img/promos/year-end.png',
            'promo_description' => "New Year's Sale up to 70% off. Sale ends at 4th January.",
        ]);
        $promo2 = Promo::create([
            'promo_name' => "Santa’s Surprise",
            'promo_image' => 'img/promos/christmas-sale.png',
            'promo_description' => "Christmas Sale up to 50% off. Sale ends at 31th December.",
        ]);
        $promo3 = Promo::create([
            'promo_name' => "Driver’s Night",
            'promo_image' => 'img/promos/driving-promo.jpg',
            'promo_description' => "Sale on driving products.",
        ]);
        $promo4 = Promo::create([
            'promo_name' => "Super Sale",
            'promo_image' => 'img/promos/super-sale.png',
            'promo_description' => "Sale up to 75% off, limited time only.",
        ]);
        $promo5 = Promo::create([
            'promo_name' => "PB's Magical Blessing",
            'promo_image' => 'img/promos/pb-promo.jpg',
            'promo_description' => "Up to 100% off.",
        ]);
    }
}
