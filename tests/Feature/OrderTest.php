<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can create an order', function () {
    $user = User::factory()->create([
        'wallet_balance' => 1000,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 10,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(201);

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'total_amount' => 500,
    ]);
});
test('user cannot create an order with insufficient stock', function () {
    $user = User::factory()->create([
        'wallet_balance' => 10000,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 2,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 5,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(422);

    $this->assertDatabaseMissing('orders', [
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock' => 2,
    ]);
});
test('user cannot create an order with insufficient wallet balance', function () {
    $user = User::factory()->create([
        'wallet_balance' => 100,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 10,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(422);

    $this->assertDatabaseMissing('orders', [
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock' => 10,
    ]);
});
test('order creates order items correctly', function () {
    $user = User::factory()->create([
        'wallet_balance' => 5000,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 10,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(201);

    $order = Order::where('user_id', $user->id)->first();

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'vendor_id' => $vendor->id,
        'quantity' => 2,
        'price' => 500,
        'item_status' => 'pending',
    ]);
});
test('creating an order decreases product stock', function () {
    $user = User::factory()->create([
        'wallet_balance' => 5000,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 10,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 3,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'stock' => 7,
    ]);
});
test('creating an order decreases user wallet balance', function () {
    $user = User::factory()->create([
        'wallet_balance' => 1000,
    ]);

    $vendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'price' => 500,
        'stock' => 10,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(201);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'wallet_balance' => 500,
    ]);
});
test('user cannot create an order with invalid data', function () {
    $user = User::factory()->create([
        'wallet_balance' => 5000,
    ]);

    $token = $user->createToken('test-token');

    $orderData = [
        'items' => [
            [
                'product_id' => 999999,
                'quantity' => 0,
            ],
        ],
    ];

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/orders', $orderData);

    $response->assertStatus(422);

    $response->assertJsonValidationErrors([
        'items.0.product_id',
        'items.0.quantity',
    ]);
});
test('unauthenticated user cannot create an order', function () {
    $orderData = [
        'items' => [
            [
                'product_id' => 1,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->postJson('/api/orders', $orderData);

    $response->assertStatus(401);

    $this->assertDatabaseCount('orders', 0);
});
