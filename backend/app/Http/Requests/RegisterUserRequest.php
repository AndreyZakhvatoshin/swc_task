<?php

namespace App\Http\Requests;

use App\Dto\RegisterUserDto;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function toData(): RegisterUserDTO
    {
        return RegisterUserDto::from($this->validated());
    }
}
