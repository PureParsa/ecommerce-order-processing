<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create vendors
        $vendors = Vendor::factory()
            ->count(3)
            ->create();

        // Create one user account for each vendor
        foreach ($vendors as $vendor) {
            User::factory()->create([
                'vendor_id' => $vendor->id,
            ]);
        }

        // Create products for each vendor
        foreach ($vendors as $vendor) {
            Product::factory()
                ->count(5)
                ->create([
                    'vendor_id' => $vendor->id,
                ]);
        }
        $customers = User::factory()
            ->count(10)
            ->create();
        foreach ($customers as $customer) {
            Order::factory()
                ->count(2)
                ->create([
                    'user_id' => $customer->id,
                ])
                ->each(function (Order $order) {
                    $products = Product::inRandomOrder()
                        ->limit(fake()->numberBetween(2, 3))
                        ->get();

                    $total = 0;

                    foreach ($products as $product) {
                        $quantity = fake()->numberBetween(1, 3);

                        OrderItem::factory()
                            ->create([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'vendor_id' => $product->vendor_id,
                                'quantity' => $quantity,
                                'price' => $product->price,
                            ]);

                        $total += $product->price * $quantity;
                    }

                    $order->update([
                        'total_amount' => $total,
                    ]);
                });
        }
    }
}
