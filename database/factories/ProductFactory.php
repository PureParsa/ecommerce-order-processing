<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'vendor_id' => Vendor::factory(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(1, 10, 100000),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
