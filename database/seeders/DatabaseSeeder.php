<?php

namespace Database\Seeders;

use App\Models\FlashSaleProduct;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPromo;
use App\Models\Promo;
use App\Models\Shipment;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->cleanPublicStorage();

        $this->call([
            ShipmentSeeder::class,
            PromoSeeder::class,
        ]);

        $this->seedCoreData();
        $this->seedTransactionData(fake('id_ID'));
    }

    private function cleanPublicStorage(): void
    {
        $directories = Storage::disk('public')->directories();

        foreach ($directories as $directory) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }

    private function seedCoreData(): void
    {
        $mainUserCount = 1200;
        $supportMerchantCount = 120;
        $supportCategoryCount = 25;
        $mainProductCount = 1200;
        $supportProductPromoCount = 300;
        $supportFlashSaleCount = 50;

        User::factory($mainUserCount)->create([
            'image' => 'img/logo/user.png',
        ]);

        Merchant::factory($supportMerchantCount)->create([
            'image' => 'img/logo/logo.png',
            'banner_image' => 'img/logo/banner-merchant.jpeg',
        ]);

        ProductCategory::factory($supportCategoryCount)->create();
        Product::factory($mainProductCount)->create();
        ProductPromo::factory($supportProductPromoCount)->create();
        FlashSaleProduct::factory($supportFlashSaleCount)->create();
    }

    private function seedTransactionData(Generator $faker): void
    {
        $mainTransactionHeaderCount = 1000;
        $mainReviewCount = 1000;

        $userIds = User::pluck('id')->all();
        $locationIds = DB::table('locations')->pluck('id')->all();
        $shipmentIds = Shipment::pluck('id')->all();
        $promoNames = Promo::pluck('promo_name')->all();
        $variants = DB::table('product_variants')
            ->select('id', 'product_id', 'price')
            ->get()
            ->all();

        if (count($userIds) === 0 || count($shipmentIds) === 0 || count($variants) === 0) {
            return;
        }

        $statuses = ['Pending', 'Shipping', 'Completed', 'Rejected'];
        $headerIds = [];
        $headerRows = [];

        for ($i = 0; $i < $mainTransactionHeaderCount; $i++) {
            $headerId = (string) Str::uuid();
            $createdAt = $faker->dateTimeBetween('-10 months', 'now');

            $headerRows[] = [
                'id' => $headerId,
                'user_id' => $faker->randomElement($userIds),
                'location_id' => count($locationIds) > 0 ? $faker->optional(0.8)->randomElement($locationIds) : null,
                'date' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
            $headerIds[] = $headerId;

            if (count($headerRows) >= 500) {
                DB::table('transaction_headers')->insert($headerRows);
                $headerRows = [];
            }
        }

        if (count($headerRows) > 0) {
            DB::table('transaction_headers')->insert($headerRows);
        }

        $detailRows = [];

        foreach ($headerIds as $headerId) {
            $itemsPerTransaction = random_int(1, 3);

            for ($j = 0; $j < $itemsPerTransaction; $j++) {
                $variant = $faker->randomElement($variants);
                $quantity = random_int(1, 5);
                $price = max(1000, (int) $variant->price);
                $discount = $faker->boolean(60) ? $faker->numberBetween(0, 35) : 0;
                $subtotal = $quantity * $price;
                $totalPaid = (int) round($subtotal * ((100 - $discount) / 100));
                $createdAt = $faker->dateTimeBetween('-10 months', 'now');

                $detailRows[] = [
                    'transaction_id' => $headerId,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'shipment_id' => $faker->randomElement($shipmentIds),
                    'status' => $faker->randomElement($statuses),
                    'promo_name' => $discount > 0 ? $faker->randomElement($promoNames) : null,
                    'discount' => $discount,
                    'total_paid' => $totalPaid,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                if (count($detailRows) >= 1000) {
                    DB::table('transaction_details')->insert($detailRows);
                    $detailRows = [];
                }
            }
        }

        if (count($detailRows) > 0) {
            DB::table('transaction_details')->insert($detailRows);
        }

        $transactionUserMap = DB::table('transaction_headers')->pluck('user_id', 'id')->all();
        $reviewSourceRows = DB::table('transaction_details')
            ->select('transaction_id', 'product_id', 'variant_id')
            ->limit($mainReviewCount)
            ->get()
            ->all();

        $reviewRows = [];
        $reviewImageRows = [];
        $reviewVideoRows = [];
        $reviewReplyRows = [];

        foreach ($reviewSourceRows as $index => $source) {
            $reviewId = (string) Str::uuid();
            $createdAt = $faker->dateTimeBetween('-8 months', 'now');
            $userId = $transactionUserMap[$source->transaction_id] ?? $faker->randomElement($userIds);

            $reviewRows[] = [
                'id' => $reviewId,
                'user_id' => $userId,
                'transaction_id' => $source->transaction_id,
                'product_id' => $source->product_id,
                'variant_bought' => $source->variant_id,
                'review' => $faker->numberBetween(1, 5),
                'message' => $faker->sentence(10),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            if ($index < 300) {
                $reviewImageRows[] = [
                    'id' => (string) Str::uuid(),
                    'review_id' => $reviewId,
                    'image' => 'img/logo/logo.png',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            if ($index < 120) {
                $reviewVideoRows[] = [
                    'id' => (string) Str::uuid(),
                    'review_id' => $reviewId,
                    'video' => 'videos/review-sample-' . (($index % 10) + 1) . '.mp4',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            if ($index < 220) {
                $reviewReplyRows[] = [
                    'id' => (string) Str::uuid(),
                    'review_id' => $reviewId,
                    'reply' => $faker->sentence(8),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            if (count($reviewRows) >= 500) {
                DB::table('reviews')->insert($reviewRows);
                $reviewRows = [];
            }
        }

        if (count($reviewRows) > 0) {
            DB::table('reviews')->insert($reviewRows);
        }

        if (count($reviewImageRows) > 0) {
            DB::table('review_images')->insert($reviewImageRows);
        }

        if (count($reviewVideoRows) > 0) {
            DB::table('review_videos')->insert($reviewVideoRows);
        }

        if (count($reviewReplyRows) > 0) {
            DB::table('review_replies')->insert($reviewReplyRows);
        }
    }
}
