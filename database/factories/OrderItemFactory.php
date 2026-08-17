<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'vendor_id' => null,
            'quantity' => fake()->numberBetween(1, 5),
            'price' => 0,
            'item_status' => 'pending',
            'last_failed_job' => null,
        ];
    }
}
