<?php

namespace App\Repositories;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function findByEmail($email): User
    {
        try {
            return $this->model->query()->where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new InvalidCredentialsException();
        }

    }

    /**
     * @throws InvalidCredentialsException
     */
    public function checkPassword($userPassword, string $requestPassword): bool
    {
        $isCorrectPassword = Hash::check($requestPassword, $userPassword);

        if (! $isCorrectPassword) {
            throw new InvalidCredentialsException();
        }

        return $isCorrectPassword;
    }
}
