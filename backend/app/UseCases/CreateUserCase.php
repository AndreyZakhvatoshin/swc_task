<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\RegisterUserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserCase
{
    public function __invoke(RegisterUserDto $dto): User
    {
        return User::query()->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }
}
