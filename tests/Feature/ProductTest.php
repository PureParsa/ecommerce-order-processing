<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('vendor can create a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');

    $vendor = Vendor::factory()->create();

    $user->update([
        'vendor_id' => $vendor->id,
    ]);
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test product description',
        'price' => 500,
        'stock' => 10,
    ];
    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/products', $productData);

    $response->assertStatus(201);
    $this->assertDatabaseHas('products', [
        'name' => 'Test Product',
        'vendor_id' => $vendor->id,
    ]);
});
test('inactive vendor cannot create a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');

    $vendor = Vendor::factory()->create([
        'is_active' => false
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test product description',
        'price' => 500,
        'stock' => 10,
    ];
    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/products', $productData);

    $response->assertStatus(403)->assertJson([
        'message' => 'This action is unauthorized.'
    ]);
    $this->assertDatabaseMissing('products', [
        'name' => 'Test Product',
    ]);

});
test('non-vendor cannot create a product', function () {
    $user = User::factory()->create([
        'vendor_id' => null
    ]);
    $token = $user->createToken('test-token');
    $vendor = Vendor::factory()->create([
        'is_active' => false
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test product description',
        'price' => 500,
        'stock' => 10,
    ];
    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/products', $productData);

    $response->assertStatus(403)->assertJson([
        'message' => 'This action is unauthorized.'
    ]);
    $this->assertDatabaseMissing('products', [
        'name' => 'Test Product',
    ]);

});
test('vendor cannot create a product with invalid data', function () {
    $user = User::factory()->create([
        'vendor_id' => null
    ]);
    $token = $user->createToken('test-token');

    $productData = [
        'name' => 'Test Product',
        'description' => 'Test product description',
        'price' => 0,
        'stock' => 10,
    ];
    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/products', $productData);

    $response->assertStatus(422)->assertJsonValidationErrors('price');
    $this->assertDatabaseMissing('products', [
        'name' => 'Test Product',
    ]);

});
test('vendor can update their own product', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create();

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $productData = [
        'name' => 'Updated Product',
        'description' => 'Updated description',
        'price' => 1000,
        'stock' => 20,
    ];

    $response = $this->withToken($token->plainTextToken)
        ->putJson("/api/products/{$product->id}", $productData);

    $response->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'description' => 'Updated description',
        'price' => 1000,
        'stock' => 20,
    ]);
});
test('vendor cannot update another vendor product', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create();

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $anotherVendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $anotherVendor->id,
    ]);

    $token = $user->createToken('test-token');

    $productData = [
        'name' => 'Hacked Product',
        'description' => 'This should not be allowed',
        'price' => 9999,
        'stock' => 100,
    ];

    $response = $this->withToken($token->plainTextToken)
        ->putJson("/api/products/{$product->id}", $productData);

    $response->assertStatus(403);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'vendor_id' => $anotherVendor->id,
        'name' => $product->name,
    ]);
});
test('vendor can delete their own product', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create();

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
test('vendor cannot delete another vendor product', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create();

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $anotherVendor = Vendor::factory()->create();

    $product = Product::factory()->create([
        'vendor_id' => $anotherVendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'vendor_id' => $anotherVendor->id,
    ]);
});
