<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\LoginUserDto;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUserCase
{
    public function __invoke(LoginUserDto $dto)
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $user->createToken('api_token')->plainTextToken;
    }
}
