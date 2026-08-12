<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create($data);
        $token = $user->createToken('auth-token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'] , $user->password))
        {
            return null;
        }
        $token = $user->createToken('auth-token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token
        ];
    }
    public function logout(User $user)
    {
        $user->tokens()->delete();
    }
}
