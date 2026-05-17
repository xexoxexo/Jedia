<?php

namespace Database\Seeders;

use App\Models\Shipment;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipmentNames = [
            'Regular',
            'Next Day',
            'Instant 3 Jam',
            'Same Day',
            'Cargo',
            'Hemat',
            'Jumbo',
            'Express',
            'Weekend Delivery',
            'Night Service',
            'International Economy',
            'International Priority',
            'Frozen Delivery',
            'Document Courier',
            'Eco Green',
            'Flash 2 Jam',
            'Locker Pickup',
            'Drone Delivery',
            'Smart Route',
            'Member Exclusive',
        ];

        foreach ($shipmentNames as $index => $shipmentName) {
            Shipment::create([
                'name' => $shipmentName,
                'base_price' => 5000 + ($index * 2500),
                'variable_price' => 10000 + ($index * 4500),
            ]);
        }
    }
}
