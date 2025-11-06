<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\LaravelData\Data;

class LoginUserDto extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
