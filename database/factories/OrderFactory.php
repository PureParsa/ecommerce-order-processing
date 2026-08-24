<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_amount' => fake()->randomFloat(2, 10, 100000),
            'status' => 'pending',
            'payment_gateway_ref' => null,
            'last_failed_job' => null,
        ];
    }
}
