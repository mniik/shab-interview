<?php

namespace App\Services\User;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function register(array $request): Model
    {
        return $this->userRepository->create($request);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function getUser(LoginRequest $request): User
    {
        $user = $this->userRepository->findByEmail(email: $request->input('email'));

        $this->userRepository->checkPassword(userPassword: $user?->password, requestPassword: $request->input('password'));

        return $user;
    }

    public function generateToken(User $user): string
    {
        return $user->createToken('clients')->plainTextToken;
    }
}
