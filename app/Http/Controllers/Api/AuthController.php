<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(private AuthService $authService)
    {
    }
    public function register(RegisterUserRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'User created successfully.',
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ],201);
    }
    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->login($request->validated());
        if(!$result){
            return response()->json([
                'message' => 'Invalid credentials.'
            ],401);
        }
        return response()->json([
            'message' => "login success",
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ] , 200);
    }
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            "message" => "logout success"
        ] , 200);
    }
}
