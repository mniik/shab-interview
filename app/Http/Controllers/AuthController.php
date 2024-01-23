<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->register($request->validated());

        return $this->successResponse($user);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->getUser($request);

        $token = $this->userService->generateToken($user);

        return $this->successResponse($token);
    }
}
