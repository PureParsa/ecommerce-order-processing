<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

    test('user can register with valid data', function () {
    $user = [
        'name' => 'Parsa Pure',
        'email' => 'Pureparsa@gmail.com',
        'password' => 'password123',
        'phone' => '09123456789',
    ];
    $response = $this->postJson('/api/register', $user);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', [
        'email' => 'Pureparsa@gmail.com',
    ]);

});
test('user can login with valid credentials', function(){
    $user = User::factory()->create([ 'password' => Hash::make('password123')]);

    $response = $this->postJson('/api/login',
        [
            'email' => $user->email ,
            'password' => 'password123' ,
        ]);

    $response->assertStatus(200);

    $response->assertJson([
        'message' => 'login success',
    ]);

    $response->assertJsonStructure([
        'message',
        'user',
        'token',
        ]);
    });
    test('user cannot login with wrong credentials', function(){
    $user = User::factory()->create([ 'password' => Hash::make('password123')]);

    $response = $this->postJson('/api/login',
        [
            'email' => $user->email ,
            'password' => 'password' ,
        ]);

    $response->assertStatus(401);

    $response->assertJson([
        'message' => 'Invalid credentials.',
    ]);

    });
    test('authenticated user can logout', function() {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->withToken($token->plainTextToken)
            ->postJson('/api/logout');

        $response->assertStatus(200)->assertJson([
            'message' => 'logout success'
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    });

