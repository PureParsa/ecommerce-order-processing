<?php

use App\Jobs\ProcessVendorCashOut;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
uses(RefreshDatabase::class);

test('vendor can create a cashout', function () {
    Bus::fake();
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create([
        'wallet_balance' => 1000,
        'is_active' => true,
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('vendor_cash_outs', [
        'vendor_id' => $vendor->id,
        'amount' => 500,
        'status' => 'pending',
    ]);
});
test('cashout decreases vendor wallet balance', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create([
        'wallet_balance' => 1000,
        'is_active' => true,
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
        'wallet_balance' => 500,
    ]);
});
test('vendor cannot create a cashout with insufficient wallet balance', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create([
        'wallet_balance' => 100,
        'is_active' => true,
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    $response->assertStatus(422);

    $this->assertDatabaseMissing('vendor_cash_outs', [
        'vendor_id' => $vendor->id,
        'amount' => 500,
    ]);

    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
        'wallet_balance' => 100,
    ]);
});
test('non-vendor cannot create a cashout', function () {
    $user = User::factory()->create([
        'vendor_id' => null,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseCount('vendor_cash_outs', 0);
});
test('inactive vendor cannot create a cashout', function () {
    $user = User::factory()->create();

    $vendor = Vendor::factory()->create([
        'wallet_balance' => 1000,
        'is_active' => false,
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseCount('vendor_cash_outs', 0);
});
test('cashout dispatches processing job', function () {
    Bus::fake();

    $user = User::factory()->create();

    $vendor = Vendor::factory()->create([
        'wallet_balance' => 1000,
        'is_active' => true,
    ]);

    $user->update([
        'vendor_id' => $vendor->id,
    ]);

    $token = $user->createToken('test-token');

    $this->withToken($token->plainTextToken)
        ->postJson('/api/vendor/cashouts', [
            'amount' => 500,
        ]);

    Bus::assertDispatched(ProcessVendorCashOut::class);
});
